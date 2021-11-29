import React from 'react';
import { format_currency } from '../../Helper'

import moment from 'moment'

export default function OrderInformation({ order }) {
    let plan = order?.plan
    let addons = order?.addons

    return (
        <div className="tab-pane show px-7 active" id="order_information" role="tabpanel">
            <div className="pt-1">
                <p className="text-dark-75 font-weight-nirmal font-size-lg m-0 pb-7">
                    Some short information about an order
                </p>

                <div className="d-flex align-items-left pb-9">
                    <div className="symbol symbol-45 symbol-light mr-4">
                        <span className="symbol-label">
                            <div className="d-flex flex-grow-1 align-items-center p-4 rounded">
                                <div className="flex-shrink-0 text-center" style={{ width: '40px' }}>
                                    <i className="far fa-user"></i>
                                </div>
                            </div>
                        </span>
                    </div>

                    <div className="d-flex flex-column flex-grow-1">
                        <a className="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">
                            Customer
                        </a>
                        <span className="text-muted font-weight-bold">The Customer</span>
                    </div>
                    
                    <span className="font-weight-bolder label label-xl label-light-success label-inline px-3 py-5 min-w-45px">
                        {order.customer_name}
                    </span>
                </div>
                
                <div className="d-flex align-items-left pb-9">
                    <div className="symbol symbol-45 symbol-light mr-4">
                        <span className="symbol-label">
                            <div className="d-flex flex-grow-1 align-items-center p-4 rounded">
                                <div className="flex-shrink-0 text-center" style={{ width: '40px' }}>
                                    <i className="icon-xl fas fa-thermometer-half"></i>
                                </div>
                            </div> 
                        </span>
                    </div>

                    <div className="d-flex flex-column flex-grow-1">
                        <a className="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">
                            Status
                        </a>
                        <span className="text-muted font-weight-bold">Status of the Order</span>
                    </div>
                    
                    <span className="font-weight-bolder label label-xl label-light-success label-inline px-3 py-5 min-w-45px">
                        {order?.status_description?.toUpperCase()}
                    </span>
                </div> 

                <div className="d-flex align-items-left pb-9">
                    <div className="symbol symbol-45 symbol-light mr-4">
                        <span className="symbol-label">
                            <div className="d-flex flex-grow-1 align-items-center p-4 rounded">
                                <div className="flex-shrink-0 text-center" style={{ width: '40px' }}>
                                    <i className="icon-xl far fa-clock"></i>
                                </div>
                            </div>
                        </span>
                    </div>

                    <div className="d-flex flex-column flex-grow-1">
                        <a className="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">
                            Order Placed
                        </a>
                        <span className="text-muted font-weight-bold">The time when the order was placed</span>
                    </div>
                    
                    <span className="font-weight-bolder label label-xl label-light-success label-inline px-3 py-5 min-w-45px">
                        {moment(order?.created_at).format('MMMM Do YYYY, h:mm:ss a')}
                    </span>
                </div>
            </div>

            <div className="col-12">
                <div className="table-responsive">
                    <table className="table">
                        <thead>
                            <tr>
                                <th className="pl-0 font-weight-bold text-muted text-uppercase">Service</th>
                                <th className="text-right font-weight-bold text-muted text-uppercase">Quantity</th>
                                <th className="text-right font-weight-bold text-muted text-uppercase">Price</th>
                                <th className="text-right pr-0 font-weight-bold text-muted text-uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr className="font-weight-boldest">
                                <td className="pl-0 pt-7">{plan?.service_plan?.plan_name}</td>
                                <td className="text-right pt-7">1</td>
                                <td className="text-right pt-7">
                                    {format_currency(plan?.service_plan?.subscription_fee)}
                                </td>
                                <td className="text-danger pr-0 pt-7 text-right">
                                    {format_currency(plan?.service_plan?.subscription_fee)}
                                </td>
                            </tr>
                            {addons?.map((addon) => {
                                return (
                                    <tr className="font-weight-boldest border-bottom-0">
                                        <td className="border-top-0 pl-0 py-4">{addon.service_addon.addon_name}</td>
                                        <td className="border-top-0 text-right py-4">{addon.quantity}</td>
                                        <td className="border-top-0 text-right py-4">{format_currency(addon.current_fee)}</td>
                                        <td className="text-danger border-top-0 pr-0 py-4 text-right">
                                            {format_currency(addon.amount)}
                                        </td>
                                    </tr>
                                )
                            })}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="border-bottom w-100 my-13 opacity-15"></div>

            <div className="col-12">
                <div className="row">
                    <div className="col-md-6">
                        <div className="d-flex flex-column text-blue mb-5 mb-md-0">
                            <div className="font-weight-boldest font-size-h4 mb-3 title-color">BANK TRANSFER</div>
                            <div className="table-responsive">
                                <table className="table font-size-h5">
                                    <tbody>
                                        <tr className="text-blue">
                                            <td className="font-weight-boldest border-0 pl-0 w-50">Account Name:</td>
                                            <td className="border-0">Barclays UK</td>
                                        </tr>
                                        <tr className="text-blue">
                                            <td className="font-weight-boldest border-0 pl-0 w-50">Account Number:</td>
                                            <td className="border-0">1234567890934</td>
                                        </tr>
                                        <tr className="text-blue">
                                            <td className="font-weight-boldest border-0 pl-0 w-50">Code:</td>
                                            <td className="border-0">BARC0032UK</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <div className="table-responsive">
                            <table className="table text-md-right font-weight-boldest">
                                <tbody>
                                    <tr>
                                        <td className="align-middle title-color font-size-lg border-0 pt-0 pl-0 w-50">SUBTOTAL</td>
                                        <td className="align-middle text-danger font-size-h3 border-0 pt-0">
                                            {format_currency(order.amount)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td className="align-middle title-color font-size-h4 border-0 py-7 pl-0 w-50">TAXES</td>
                                        <td className="align-middle text-danger font-size-h3 border-0 py-7">
                                            {format_currency(order.vat_amount)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td className="align-middle title-color font-size-h4 border-0 pl-0 w-50">GRAND TOTAL</td>
                                        <td className="align-middle text-danger font-size-h2 border-0">
                                            {format_currency(order.total_amount)}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}