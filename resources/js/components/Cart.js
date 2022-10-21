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
            customer_id: "",
            gst: parseInt($("#gst").val())
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
                this.setState({ customer_id: res.data[0].pivot.customer_id });
                this.setState({ cart });
            } else {
                this.setState({ customer_id: "" });
                this.setState({ cart: [] });
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

    getTotal(cart) {
        const total = cart.map(c => c.pivot.quantity * c.price);
        return sum(total).toFixed(2);
    }

    getGstAmount(cart) {
        let subtotal = cart.map(c => c.pivot.quantity * c.price);
        return (sum(subtotal) / 100) * this.state.gst;
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
            inputValue: this.getTotal(this.state.cart),
            showCancelButton: true,
            confirmButtonText: "Send",
            showLoaderOnConfirm: true,
            preConfirm: amount => {
                return axios
                    .post("/admin/orders", {
                        customer_id: this.state.customer_id,
                        amount
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
            <div className="row">
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
                <div className="printdev" ref={el => (this.componentRef = el)}>
                    <div class="ticket">
                        <p class="centered">
                            <img src="http://127.0.0.1:8000/images/logo.png" />
                            <br />
                            Kandaan Plaza F7, Markaz
                            <br />
                            Jani Babu Barbecue
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
                                            {c.price * c.pivot.quantity}{" "}
                                            {window.APP.currency_symbol}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colSpan="2" className="text-right">
                                        Sub Total:
                                    </th>
                                    <th className="text-center">
                                        {window.APP.currency_symbol}{" "}
                                        {this.getTotal(cart)}
                                    </th>
                                </tr>
                                <tr>
                                    <th colSpan="2" className="text-right">
                                        GST {this.state.gst}%:
                                    </th>
                                    <th className="text-center">
                                        {window.APP.currency_symbol}{" "}
                                        {this.getGstAmount(cart)}
                                    </th>
                                </tr>
                                <tr>
                                    <th colSpan="2" className="text-right">
                                        Total:
                                    </th>
                                    <th className="text-center">
                                        {window.APP.currency_symbol}{" "}
                                        {parseInt(this.getTotal(cart)) +
                                            parseInt(this.getGstAmount(cart))}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                        <p class="centered">
                            Thanks for your purchase!
                            <br />
                            parzibyte.me/blog
                        </p>
                    </div>
                </div>
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
                                <option value="">Select Table</option>
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
                                        <th className="text-right">Price</th>
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
                                                            event.target.value
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
                                                    c.price * c.pivot.quantity
                                                ).toFixed(2)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col">Sub Total:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol} {this.getTotal(cart)}
                        </div>
                    </div>
                    <div className="row">
                        <div className="col">GST {this.state.gst}%:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol}{" "}
                            {this.getGstAmount(cart)}
                        </div>
                    </div>
                    <div className="row">
                        <div className="col">Total:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol}

                            {parseInt(this.getTotal(cart)) +
                                parseInt(this.getGstAmount(cart))}
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
                                        window.APP.warning_quantity > p.quantity
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
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
