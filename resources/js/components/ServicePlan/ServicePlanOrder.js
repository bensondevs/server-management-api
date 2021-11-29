import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class ServicePlanOrder extends Component {
    constructor(props) {
        super(props)

        this.state = {
            orders: {},
            isLoading: false,
        }

        this.populateOrders = this.populateOrders.bind(this)
        this.toggleLoading = this.toggleLoading.bind(this)
    }

    async componentWillMount() {
        await this.populateOrders()
    }

    async populateOrders(pageNumber = 1) {
        this.toggleLoading()

        let servicePlanComponent = document.getElementById('service_plan_order')
        let id = servicePlanComponent.getAttribute('data-plan-id')
        await axios.get('/dashboard/service_plans/orders?id=' + id + '&page=' + pageNumber)
            .then((response) => {
                console.log(response.data.orders)

                this.setState({ 
                    orders: response.data.orders,
                })
            })

        this.toggleLoading()
    }

    renderOrdersTable() {
        let orders = this.state.orders
        let data = orders ? orders?.data : [] 

        if (data instanceof Object)
            data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Service Plan</th>
                    <th>Customer Name</th>
                    <th>Payment Status</th>
                    <th>Container ID</th>
                    <th>Amount</th>
                    <th>VAT Size</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((order, key) => {
                            return (
                                <tr key={order.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{order.service_name}</td>
                                    <td>{order.customer_name}</td>
                                    <td>{this.statusLabel(order)}</td>
                                    <td>{this.containerLink(order)}</td>
                                    <td>{order.amount}</td>
                                    <td>{order.vat_size}</td>
                                    <td>{order.total_amount}</td>
                                    <td>{order.payment_method}</td>
                                    <td>
                                        {this.editButton(order)}
                                        {this.deleteButton(order)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    renderPagination() {
        let orders = this.state.orders

        let currentPage = orders.current_page
        let perPage = orders.per_page
        let total = orders.total

        return orders ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateOrders.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    deleteButton(order) {
        return (
            <a href={'/dashboard/orders/confirm_delete?id=' + order.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            orders: searchInCollection(_.clone(this.state.allOrders), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.orders)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    statusLabel(order) {
        let status = order.status
        
        let label
        if (status == 'unpaid') {
            label = (
                <span className="badge badge-secondary">
                    Unpaid
                </span>
            )
        } else if (status == 'paid') {
            label = (
                <span className="badge badge-primary">
                    Paid
                </span>
            )
        } else if (status == 'activated') {
            label = (
                <span className="badge badge-success">
                    Activated
                </span>
            )
        } else if (status == 'expired') {
            label = (
                <span className="badge badge-dark">
                    Expired
                </span>
            )
        } else if (status == 'destroyed') {
            label = (
                <span className="badge badge-danger">
                    Expired
                </span>
            )
        } else {
            label = (
                <span className="badge badge-light">
                    Unknown
                </span>
            )
        }

        return label
    }

    containerLink(order) {
        return order.container_id ?
        (
            <a href={'/dashboard/containers/edit?id=' + order.container_id}>
                {order.container_id}
            </a>
        ) : (
            <a className="btn btn-sm btn-primary" href={'/dashboard/containers/create?order_id=' + order.id + '&customer_id=' + order.customer_id}>
                <i className="fas fa-folder-plus mr-1"></i> Create Container
            </a>
        )
    }

    editButton(order) {
        return (
            <a href={'/dashboard/orders/edit?id=' + order.id}>
                <img src="/assets/media/svg/icons/General/Edit.svg" />
            </a>
        )
    }

    deleteButton(order) {
        return (
            <a href={'/dashboard/orders/confirm_delete?id=' + order.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    render() {
        return (
            <div>
                <div className="mt-2 mb-5 mt-lg-5 mb-lg-10">
                    <div className="row align-items-center">
                        <div className="col-lg-9 col-xl-8">
                            <div className="row align-items-center">
                                <div className="col-md-4 my-2 my-md-0">
                                    <div className="input-icon">
                                        <input 
                                            onChange={(event) => this.search(event)}
                                            type="text" 
                                            className="form-control" 
                                            placeholder="Search..." />
                                        <span><i className="flaticon2-search-1 text-muted"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <LoadingOverlay
                    active={this.state.isLoading}
                    spinner
                    text="Loading Command Histories...">
                    {this.renderOrdersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('service_plan_order')) {
    ReactDOM.render(<ServicePlanOrder />, document.getElementById('service_plan_order'));
}