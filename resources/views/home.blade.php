@extends('layouts.admin')
@section('content-header', '')
@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-lg-3 col-6">
         <div class="dash__widget d-flex">
            <div class="dash__widget__img">
               <span class="d-flex align-items-center justify-content-center">
               <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/dash1.svg" alt="">
               </span>
            </div>
            <div class="dash__widget__content ml-3">
               <h5>{{config('settings.currency_symbol')}} <span class="counters" data-count="307144.00">307144</span></h5>
               <h6>Total Purchase Due</h6>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-6">
         <div class="dash__widget d-flex">
            <div class="dash__widget__img">
               <span class="d-flex align-items-center justify-content-center dash1">
               <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/dash2.svg" alt="">
               </span>
            </div>
            <div class="dash__widget__content ml-3">
               <h5>{{config('settings.currency_symbol')}} <span class="counters" data-count="307144.00">307144</span></h5>
               <h6>Total Sales Due</h6>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-6">
         <div class="dash__widget d-flex">
            <div class="dash__widget__img">
               <span class="d-flex align-items-center justify-content-center dash2">
               <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/dash3.svg" alt="">
               </span>
            </div>
            <div class="dash__widget__content ml-3">
               <h5>{{config('settings.currency_symbol')}} <span class="counters" data-count="307144.00">307144</span></h5>
               <h6>Total Sale Amount</h6>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-6">
         <div class="dash__widget d-flex">
            <div class="dash__widget__img">
               <span class="d-flex align-items-center justify-content-center dash3">
               <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/dash4.svg" alt="">
               </span>
            </div>
            <div class="dash__widget__content ml-3">
               <h5>{{config('settings.currency_symbol')}} <span class="counters" data-count="307144.00">307144</span></h5>
               <h6>Total Sale Amount</h6>
            </div>
         </div>
      </div>
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <a href="{{ route('customers.index') }}">
            <div class="dash__count d-flex justify-content-between align-items-center">
               <div class="dash__counts">
                  <h4>{{$orders_count}}</h4>
                  <h5>Customers</h5>
               </div>
               <div class="dash__imgs">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user">
                     <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                     <circle cx="12" cy="7" r="4"></circle>
                  </svg>
               </div>
            </div>
         </a>
         <!-- <div class="small-box bg-info">
            <div class="inner">
                <h3>{{$orders_count}}</h3>
              <p>Orders Count</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{route('orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div> -->
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <a href="{{ route('orders.index') }}">
            <div class="dash__count d-flex justify-content-between align-items-center dash1">
               <div class="dash__counts">
                  <h4>{{number_format($income, 2)}}</h4>
                  <h5>Suppliers</h5>
               </div>
               <div class="dash__imgs">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check">
                     <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                     <circle cx="8.5" cy="7" r="4"></circle>
                     <polyline points="17 11 19 13 23 9"></polyline>
                  </svg>
               </div>
            </div>
         </a>
         <!-- <div class="small-box bg-success">
            <div class="inner">
                <h3>{{config('settings.currency_symbol')}} {{number_format($income, 2)}}</h3>
              <p>Income</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="{{route('orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div> -->
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <a href="{{ route('orders.index') }}">
            <div class="dash__count d-flex justify-content-between align-items-center dash2">
               <div class="dash__counts">
                  <h4>{{config('settings.currency_symbol')}} {{number_format($income_today, 2)}}</h4>
                  <h5>Income Today</h5>
               </div>
               <div class="dash__imgs">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                     <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                     <polyline points="14 2 14 8 20 8"></polyline>
                     <line x1="16" y1="13" x2="8" y2="13"></line>
                     <line x1="16" y1="17" x2="8" y2="17"></line>
                     <polyline points="10 9 9 9 8 9"></polyline>
                  </svg>
               </div>
            </div>
         </a>
      </div>
      <!-- <div class="small-box bg-danger">
         <div class="inner">
           <h3>{{config('settings.currency_symbol')}} {{number_format($income_today, 2)}}</h3>
         
           <p>Income Today</p>
         </div>
         <div class="icon">
           <i class="ion ion-pie-graph"></i>
         </div>
         <a href="{{route('orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
         </div>
         </div> -->
      <!-- ./col -->
      <div class="col-lg-3 col-6">
         <!-- small box -->
         <a href="{{ route('orders.index') }}">
            <div class="dash__count d-flex justify-content-between align-items-center dash3">
               <div class="dash__counts">
                  <h4>{{$customers_count}}</h4>
                  <h5>Sales Invoice</h5>
               </div>
               <div class="dash__imgs">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file">
                     <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                     <polyline points="13 2 13 9 20 9"></polyline>
                  </svg>
               </div>
            </div>
         </a>
         <!-- <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{$customers_count}}</h3>
            
              <p>Customers Count</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="{{ route('customers.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div> -->
      </div>
      <!-- ./col -->

      <div class="col-lg-12 col-12">
        <div class="recent__added__table common__table">
          <h4 class="card-title mb-4">Recently Added Products</h4>
          <div class="table-responsive"><table class="table">
            <thead>
              <tr>
                <th>Sno</th>
                <th>Products</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td class="product__img__name">
                  <a>
                    <img src="{{asset('images/maxican.png')}}" alt="">
                  </a>
                  <a>Maxican</a>
                </td>
                <td>{{config('settings.currency_symbol')}} 891.2</td>
              </tr>
              <tr>
                <td>2</td>
                <td class="product__img__name">
                  <a>
                    <img src="{{asset('images/Indian.png')}}" alt="">
                  </a>
                  <a>Indian</a>
                </td>
                <td>{{config('settings.currency_symbol')}} 891.2</td>
              </tr>
              <tr>
                <td>3</td>
                <td class="product__img__name">
                  <a>
                    <img src="{{asset('images/japanese.png')}}" alt="">
                  </a>
                  <a>Japanese</a>
                </td>
                <td>{{config('settings.currency_symbol')}} 891.2</td>
              </tr>
            </tbody>
          </table></div>
        </div>
      </div>
      <div class="col-lg-12 col-12">
        <div class="recent__added__table common__table">
          <h4 class="card-title mb-4">Expired Products</h4>
          <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Sno</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Brand Name</th>
                <th>Category Name</th>
                <th>Expiry Date</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>IT0001</td>
                <td class="product__img__name">
                  <a>
                    <img src="{{asset('images/maxican.png')}}" alt="">
                  </a>
                  <a>Maxican</a>
                </td>
                <td>N/D</td>
                <td>Fruits</td>
                <td>12-12-2022</td>
              </tr>
              <tr>
                <td>2</td>
                <td>IT0002</td>
                <td class="product__img__name">
                  <a>
                    <img src="{{asset('images/Indian.png')}}" alt="">
                  </a>
                  <a>Indian</a>
                </td>
                <td>N/D</td>
                <td>Fruits</td>
                <td>12-12-2022</td>
              </tr>
              <tr>
                <td>3</td>
                <td>IT0003</td>
                <td class="product__img__name">
                  <a>
                    <img src="{{asset('images/japanese.png')}}" alt="">
                  </a>
                  <a>Japanese</a>
                </td>
                <td>N/D</td>
                <td>Fruits</td>
                <td>12-12-2022</td>
              </tr>
            </tbody>
          </table>
          </div>
        </div>
      </div>
   </div>
</div>
@endsection