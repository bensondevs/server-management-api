import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { urlParams } from '../Helper'
import Pagination from '../Pagination'
import LoadingOverlay from 'react-loading-overlay';
import Order from './Order';
import OrderInformation from './Tabs/OrderInformation';
import OrderServicePlan from './Tabs/OrderServicePlan';
import OrderPaymentInformation from './Tabs/OrderPaymentInformation';

export default class OrderView extends Component {
    constructor(props) {
        super(props)

        this.state = {
            isLoading: false,

            order: {},
            queueName: '',
        }

        this.toggleLoading = this.toggleLoading.bind(this)
    }

    componentDidMount() {
        this.loadOrder()
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    countTotal() {
        let order = this.state.order

    }

    async loadOrder() {
        await axios.get('/dashboard/orders/load?id=' + urlParams().get('id'))
            .then((response) => {
                console.log(response.data)
                this.setState({ order: response.data.order })
            }).catch((error) => console.log(error.response.data))
    }

    render() {
        let order = this.state.order
        let servicePlan = order?.service_plan
        let payment = order?.payment

        return (
            <div className="card card-custom">
                <div className="card-header card-header-tabs-line nav-tabs-line-3x">
                    <div className="card-toolbar">
                        <ul className="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                            <li className="nav-item">
                                <a className="nav-link active" data-toggle="tab" href="#order_information">
                                    <span className="nav-icon mr-3">
                                        <span className="svg-icon svg-icon-2x mr-1">
                                            <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                                    <path d="M4.85714286,1 L11.7364114,1 C12.0910962,1 12.4343066,1.12568431 12.7051108,1.35473959 L17.4686994,5.3839416 C17.8056532,5.66894833 18,6.08787823 18,6.52920201 L18,19.0833333 C18,20.8738751 17.9795521,21 16.1428571,21 L4.85714286,21 C3.02044787,21 3,20.8738751 3,19.0833333 L3,2.91666667 C3,1.12612489 3.02044787,1 4.85714286,1 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fillRule="nonzero" opacity="0.3"/>
                                                    <path d="M6.85714286,3 L14.7364114,3 C15.0910962,3 15.4343066,3.12568431 15.7051108,3.35473959 L20.4686994,7.3839416 C20.8056532,7.66894833 21,8.08787823 21,8.52920201 L21,21.0833333 C21,22.8738751 20.9795521,23 19.1428571,23 L6.85714286,23 C5.02044787,23 5,22.8738751 5,21.0833333 L5,4.91666667 C5,3.12612489 5.02044787,3 6.85714286,3 Z M8,12 C7.44771525,12 7,12.4477153 7,13 C7,13.5522847 7.44771525,14 8,14 L15,14 C15.5522847,14 16,13.5522847 16,13 C16,12.4477153 15.5522847,12 15,12 L8,12 Z M8,16 C7.44771525,16 7,16.4477153 7,17 C7,17.5522847 7.44771525,18 8,18 L11,18 C11.5522847,18 12,17.5522847 12,17 C12,16.4477153 11.5522847,16 11,16 L8,16 Z" fill="#000000" fillRule="nonzero"/>
                                                </g>
                                            </svg>
                                        </span>
                                    </span>
                                    <span className="nav-text font-size-lg">Order Information</span>
                                </a>
                            </li>
                            <li className="nav-item mr-3">
                                <a className="nav-link" data-toggle="tab" href="#payment_information">
                                    <span className="nav-icon mr-3">
                                        <span className="svg-icon">
                                            <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fillRule="nonzero" opacity="0.3"></path>
                                                    <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fillRule="nonzero"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </span>
                                    <span className="nav-text font-size-lg">Payment Information</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="card-body">
                    <div className="tab-content">
                        <OrderInformation order={order}></OrderInformation>
                        {/* <OrderServicePlan servicePlan={servicePlan}></OrderServicePlan> */}
                        <OrderPaymentInformation payment={payment}></OrderPaymentInformation>
                    </div>
                </div>
            </div>
        )
    }
}

if (document.getElementById('order_view')) {
    ReactDOM.render(<OrderView />, document.getElementById('order_view'));
}