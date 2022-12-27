import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";
import ReactToPrint from "react-to-print";
import $ from "jquery";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            customers: [],
            barcode: "",
            search: "",
            customer_id: 1,
            gst: parseInt($("#gst").val()),
            posCharges: parseFloat($("#posCharges").val()),
            paymentCard: false,
            cardPrice: 0.0,
            discount: 0.0,
            discountValue: 0.0
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.changePosstate = this.changePosstate.bind(this);
        this.handleDiscount = this.handleDiscount.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then(res => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then(res => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadCart() {
        axios.get("/admin/cart").then(res => {
            const cart = res.data;
            if (res.data.length > 0) {
                console.log(res.data[0].pivot.customer_id);
                this.setState({ customer_id: res.data[0].pivot.customer_id });
                this.setState({ cart });
                this.setState({
                    paymentCard: false,
                    cardPrice: 0.0,
                    discount: 0.0,
                    discountValue: 0.0
                });
            } else {
                this.setState({ customer_id: 1 });
                this.setState({
                    cart: [],
                    paymentCard: false,
                    cardPrice: 0.0,
                    discount: 0.0,
                    discountValue: 0.0
                });
            }
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then(res => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const cart = this.state.cart.map(c => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ cart });

        axios
            .post("/admin/cart/change-qty", { product_id, quantity: qty })
            .then(res => {})
            .catch(err => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    handleDiscount(event, cart) {
        let inputval = event.target.value;
        let subtotal = cart.map(c => c.pivot.quantity * c.price);
        let discountamount = (sum(subtotal) / 100) * inputval;
        this.setState({ discount: discountamount, discountValue: inputval });
    }

    getTotal(cart) {
        const total = cart.map(c => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }

    total(cart) {
        console.log(this.state.paymentCard);
        return Number(this.getTotal(cart)) + this.getGstAmount(cart);
    }

    getGstAmount(cart) {
        let subtotal = cart.map(c => c.pivot.quantity * c.price);
        return (sum(subtotal) / 100) * this.state.gst;
    }

    getPosCharges(cart) {
        let subtotal = cart.map(c => c.pivot.quantity * c.price);
        return (sum(subtotal) / 100) * this.state.posCharges;
    }
    changePosstate(cart) {
        const posState = this.state.paymentCard ? false : true;
        const posCharges = posState ? this.getPosCharges(cart) : 0;
        this.setState({
            paymentCard: posState,
            cardPrice: posCharges
        });
    }

    handleClickDelete(product_id) {
        axios
            .post("/admin/cart/delete", { product_id, _method: "DELETE" })
            .then(res => {
                const cart = this.state.cart.filter(c => c.id !== product_id);
                this.setState({ cart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/cart/empty", { _method: "DELETE" }).then(res => {
            this.setState({ cart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode, pid) {
        let product = this.state.products.find(p => p.barcode === barcode);
        let customerid = this.state.customer_id;
        console.log(customerid);
        if (!!product) {
            // if product is already in cart
            let cart = this.state.cart.find(c => c.id === product.id);
            if (!!cart) {
                // update quantity
                this.setState({
                    cart: this.state.cart.map(c => {
                        if (
                            c.id === product.id &&
                            product.quantity > c.pivot.quantity
                        ) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    })
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 1,
                            product_id: product.id,
                            user_id: 1
                        }
                    };

                    this.setState({ cart: [...this.state.cart, product] });
                }
            }

            axios
                .post("/admin/cart", { barcode, pid, customerid })
                .then(res => {
                    console.log(res);
                })
                .catch(err => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(event) {
        let customerid = event.target.value;
        this.setState({ customer_id: event.target.value });
        axios.post("/admin/getcart", { customerid }).then(res => {
            const cart = res.data;
            this.setState({ cart });
        });
    }
    handleClickSubmit() {
        Swal.fire({
            title: "Received Amount",
            input: "text",
            inputValue: parseFloat(
                this.total(this.state.cart) +
                    this.state.cardPrice -
                    this.state.discount
            ).toFixed(2),
            showCancelButton: true,
            confirmButtonText: "Send",
            showLoaderOnConfirm: true,
            preConfirm: amount => {
                return axios
                    .post("/admin/orders", {
                        customer_id: this.state.customer_id,
                        amount,
                        cardPrice: this.state.cardPrice,
                        discount: this.state.discount,
                        discountValue: this.state.discountValue
                    })
                    .then(res => {
                        $(".printdev").addClass("active");
                        document.getElementById("printbtn").click();
                        this.loadCart();
                        //return res.data;
                    })
                    .catch(err => {
                        Swal.showValidationMessage(err.response.data.message);
                    });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then(result => {
            if (result.value) {
                //
            }
        });
    }
    render() {
        const { cart, products, customers, barcode } = this.state;
        return (
            <div>
                <div>
                    <ReactToPrint
                        trigger={() => {
                            return (
                                <button className="d-none" id="printbtn">
                                    Print
                                </button>
                            );
                        }}
                        content={() => this.componentRef}
                        documentTitle="new Doucment"
                        pageStyle="print"
                    />
                </div>
                <div class="printdev" ref={el => (this.componentRef = el)}>
                    <p class="centered">
                        <img src="http://127.0.0.1:8000/images/logo.png" />
                        <br />
                        Kandaan Plaza F7, Markaz
                        <br />
                        Jani Babu Barbecue
                        <br />
                        03168259551
                    </p>
                    <table>
                        <thead>
                            <tr>
                                <th class="quantity">Q.</th>
                                <th class="description">Name</th>
                                <th class="price">$$</th>
                            </tr>
                        </thead>
                        <tbody>
                            {cart.map(c => (
                                <tr key={c.id}>
                                    <td class="quantity">
                                        {" "}
                                        {c.pivot.quantity}
                                    </td>
                                    <td class="description">{c.name}</td>
                                    <td class="price">
                                        {window.APP.currency_symbol}{" "}
                                        {parseFloat(
                                            c.price * c.pivot.quantity
                                        ).toFixed(2)}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Sub Total:</th>
                                <th>
                                    {window.APP.currency_symbol}{" "}
                                    {parseFloat(this.getTotal(cart)).toFixed(2)}
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>GST {this.state.gst}%:</th>
                                <th>
                                    {window.APP.currency_symbol}{" "}
                                    {this.getGstAmount(cart).toFixed(2)}
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>POS Charges {this.state.posCharges}%:</th>
                                <th>
                                    {window.APP.currency_symbol}{" "}
                                    {this.getPosCharges(cart).toFixed(2)}
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Discount {this.state.discountValue}%:</th>
                                <th>
                                    {window.APP.currency_symbol}{" "}
                                    {this.state.discount}
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Total:</th>
                                <th>
                                    {window.APP.currency_symbol}{" "}
                                    {parseFloat(
                                        this.total(cart) +
                                            this.state.cardPrice -
                                            this.state.discount
                                    ).toFixed(2)}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                    <p class="centered">
                        Thanks for your purchase!
                        <br />
                        Developed by Abdul Basit Mobile #: 03115818727
                    </p>
                </div>
                <div className="row">
                    <div className="col-md-6 col-lg-4">
                        <div className="row mb-2">
                            <div className="col">
                                <form onSubmit={this.handleScanBarcode}>
                                    <input
                                        type="text"
                                        className="form-control"
                                        placeholder="Scan Barcode..."
                                        value={barcode}
                                        onChange={this.handleOnChangeBarcode}
                                    />
                                </form>
                            </div>
                            <div className="col">
                                <select
                                    className="form-control"
                                    onChange={this.setCustomerId}
                                >
                                    {customers.map(cus => (
                                        <option
                                            key={cus.id}
                                            value={cus.id}
                                            selected={
                                                this.state.customer_id == cus.id
                                                    ? "selected"
                                                    : ""
                                            }
                                        >{`${cus.table_no}`}</option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="user-cart">
                            <div className="card">
                                <table className="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th className="text-right">
                                                Price
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {cart.map(c => (
                                            <tr key={c.id}>
                                                <td>{c.name}</td>
                                                <td>
                                                    <input
                                                        type="text"
                                                        className="form-control form-control-sm qty"
                                                        value={c.pivot.quantity}
                                                        onChange={event =>
                                                            this.handleChangeQty(
                                                                c.id,
                                                                event.target
                                                                    .value
                                                            )
                                                        }
                                                    />
                                                    <button
                                                        className="btn btn-danger btn-sm"
                                                        onClick={() =>
                                                            this.handleClickDelete(
                                                                c.id
                                                            )
                                                        }
                                                    >
                                                        <i className="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                                <td className="text-right">
                                                    {window.APP.currency_symbol}{" "}
                                                    {(
                                                        c.price *
                                                        c.pivot.quantity
                                                    ).toFixed(2)}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div className="row">
                            <div className="form-check">
                                <div class="col">
                                    <input
                                        className="form-check-input"
                                        type="checkbox"
                                        name="paymentType"
                                        id="paymentType"
                                        checked={this.state.paymentCard}
                                        onClick={() =>
                                            this.changePosstate(cart)
                                        }
                                    />
                                    <label
                                        className="form-check-label"
                                        for="paymentType"
                                    >
                                        Payment with Card
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div className="row" style={{ height: "38px" }}>
                            <div className="form-check">
                                <div className="col">
                                    <input
                                        className="form-check-input"
                                        type="number"
                                        name="discount"
                                        id="discount"
                                        step="0.01"
                                        placeholder="Enter Discount Rate"
                                        value={this.state.discountValue}
                                        onChange={e =>
                                            this.handleDiscount(e, cart)
                                        }
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="row">
                            <div className="col">Sub Total:</div>
                            <div className="col text-right">
                                {window.APP.currency_symbol}{" "}
                                {this.getTotal(cart)}
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">GST {this.state.gst}%:</div>
                            <div className="col text-right">
                                {window.APP.currency_symbol}{" "}
                                {this.getGstAmount(cart).toFixed(2)}
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                POS Charges {this.state.posCharges}%:
                            </div>
                            <div className="col text-right">
                                {window.APP.currency_symbol}{" "}
                                {this.state.paymentCard
                                    ? this.getPosCharges(cart).toFixed(2)
                                    : 0.0}
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                Discount {this.state.discountValue}%:
                            </div>
                            <div className="col text-right">
                                {window.APP.currency_symbol}{" "}
                                {this.state.discount.toFixed(2)}
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">Total:</div>
                            <div className="col text-right">
                                {window.APP.currency_symbol}{" "}
                                {parseFloat(
                                    this.total(cart) +
                                        this.state.cardPrice -
                                        this.state.discount
                                ).toFixed(2)}
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                <button
                                    type="button"
                                    className="btn btn-danger btn-block"
                                    onClick={this.handleEmptyCart}
                                    disabled={!cart.length}
                                >
                                    Cancel
                                </button>
                            </div>
                            <div className="col">
                                <button
                                    type="button"
                                    className="btn btn-primary btn-block"
                                    disabled={!cart.length}
                                    onClick={this.handleClickSubmit}
                                >
                                    Submit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-6 col-lg-8">
                        <div className="mb-2">
                            <input
                                type="text"
                                className="form-control"
                                placeholder="Search Product..."
                                onChange={this.handleChangeSearch}
                                onKeyDown={this.handleSeach}
                            />
                        </div>
                        <div className="order-product">
                            {products.map(p => (
                                <div
                                    onClick={() =>
                                        this.addProductToCart(p.barcode, p.id)
                                    }
                                    key={p.id}
                                    className="item"
                                >
                                    <img src={p.image_url} alt="" />
                                    <h5
                                        style={
                                            window.APP.warning_quantity >
                                            p.quantity
                                                ? { color: "red" }
                                                : {}
                                        }
                                    >
                                        {p.name}({p.quantity})
                                    </h5>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
