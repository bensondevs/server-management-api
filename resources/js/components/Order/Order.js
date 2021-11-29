import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import ReactPaginate from 'react-paginate';

import LoadingOverlay from 'react-loading-overlay';
import { PerPageOptions } from '../PerPageOptions';
import { SearchInput } from '../SearchInput';

export default class Order extends Component {
    constructor(props) {
        super(props)

        this.state = {
            orders: {},
            badges: [],
            isLoading: false,

            currentPage: 1,
            offset: 0,
            perPage: 10,

            search: '',
            isTyping: false,
            typingTimeout: 0,
        }

        this.populateOrders = this.populateOrders.bind(this);
        this.loadStatusBadges = this.loadStatusBadges.bind(this);

        this.toggleLoading = this.toggleLoading.bind(this);

        this.renderOrdersTable = this.renderOrdersTable.bind(this);
        this.renderPagination = this.renderPagination.bind(this);
        this.renderPerPageOptions = this.renderPerPageOptions.bind(this);
        this.statusBadge = this.statusBadge.bind(this);

        this.handleSearch = this.handleSearch.bind(this);
        this.handlePaginationClick = this.handlePaginationClick.bind(this);
        this.onPerPageOptionChange = this.onPerPageOptionChange.bind(this);
    }

    async componentWillMount() {
        await this.loadStatusBadges();
        await this.populateOrders();
    }

    async populateOrders(pageNumber = 1) {
        this.toggleLoading();

        let url = `/dashboard/orders?per_page=${this.state.perPage}&page=${pageNumber}`;
        if (this.state.search) {
            url += '&search=' + encodeURI(this.state.search);
        }

        await axios.get(url).then((response) => {
            this.setState({ orders: response.data.orders }, () => console.log(this.state.orders));
        });

        this.toggleLoading();
    }

    async loadStatusBadges() {
        let request = await axios.get('/meta/order/status_badges');
        let badges = request.data;
        
        await this.setState({ badges: badges });
    }

    statusBadge(status) {
        if (! this.state.badges) {
            this.loadStatusBadges();
        }

        let badges = this.state.badges;
        let badge = badges[status];

        if (! badge) {
            return (<span className="label label-secondary label-inline font-weight-lighter mr-2">Unknown</span>);
        }

        return (<span className={badge.class}>{badge.content}</span>);
    }

    renderOrdersTable() {
        let orders = this.state.orders
        let data = orders ? orders?.data : [] 

        if (data instanceof Object) data = Object.values(data)

        return (
            <>
                <table className="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer Name</th>
                        <th>Payment Status</th>
                        <th>Container ID</th>
                        <th>Amount</th>
                        <th>VAT Size</th>
                        <th>Total Amount</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                            {data.map((order, key) => {
                                return (
                                    <tr key={order.id}>
                                        <td>{order.order_number}</td>
                                        <td>{order.customer_name}</td>
                                        <td>{this.statusBadge(order.status)}</td>
                                        <td>{this.containerLink(order)}</td>
                                        <td>{order.amount}</td>
                                        <td>{order.vat_size}</td>
                                        <td>{order.total_amount}</td>
                                        <td>
                                            {this.viewButton(order)}
                                            {this.deleteButton(order)}
                                        </td>
                                    </tr>
                                )
                            })}
                    </tbody>
                </table>
            </>
        )
    }

    renderPagination() {
        return (
            <ReactPaginate
                previousLabel={'previous'}
                previousLinkClassName={'page-link'}
                nextLabel={'next'}
                nextLinkClassName={'page-link'}
                disabledClassName={'disabled'}
                breakLabel={'...'}
                breakClassName={'page-link disabled'}
                pageCount={this.state.orders.last_page}
                marginPagesDisplayed={2}
                pageRangeDisplayed={5}
                onPageChange={this.handlePaginationClick}
                containerClassName={'pagination'}
                pageClassName={'page-item'}
                pageLinkClassName={'page-link'}
                activeClassName={'active'} />
        )
    }

    handlePaginationClick(data) {
        let selectedPage = data.selected + 1;
        let offset = Math.ceil(selectedPage * this.state.perPage);

        console.log(selectedPage);

        this.setState({ offset: offset, currentPage: selectedPage }, () => {
            this.populateOrders(selectedPage);
        });
    }

    deleteButton(order) {
        return (
            <a href={'/dashboard/orders/' + order.id + '/delete'}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    async handleSearch(keyword) {
        await this.setState({ search: keyword });
        await this.populateOrders();
    }

    async onPerPageOptionChange(event) {
        let perPage = event.target.value;

        await this.setState({ perPage: perPage });
        await this.populateOrders();
    }

    renderPerPageOptions() {
        return (
            <PerPageOptions 
                onValueChange={this.onPerPageOptionChange}
                defaultPerPage={this.state.perPage} />
        )
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    containerLink(order) {
        if (order.container_id) {
            return (
                <a href={'/dashboard/containers/' + order.container_id + '/edit'}>
                    {order.container_id}
                </a>
            )
        }

        if (order.status == 1) {
            return (
                <a className="btn btn-sm btn-primary" href={'/dashboard/orders/' + order.id + '/manually_create_container'}>
                    <i className="fas fa-folder-plus mr-1"></i> Create Container
                </a>
            )
        }
    }

    viewButton(order) {
        return (
            <a href={'/dashboard/orders/' + order.id + '/view'}>
                <img src="/assets/media/svg/icons/General/Visible.svg" />
            </a>
        )
    }

    editButton(order) {
        return (
            <a href={'/dashboard/orders/' + order.id + '/edit'}>
                <img src="/assets/media/svg/icons/General/Edit.svg" />
            </a>
        )
    }

    deleteButton(order) {
        return (
            <a href={'/dashboard/orders/' + order.id + '/delete'}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    render() {
        let orders = this.state.orders?.data
        if (! orders) return null

        return (
            <div>
                <div className="mt-2 mb-5 mt-lg-5 mb-lg-10">
                    <div className="row align-items-center">
                        <div className="col-lg-9 col-xl-8">
                            <div className="row align-items-center">
                                <div className="col-md-4 my-2 my-md-0">
                                    <SearchInput handleSearch={this.handleSearch}></SearchInput>
                                </div>

                                <div className="col-md-2 my-2 my-md-0">
                                    {this.renderPerPageOptions()}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <LoadingOverlay
                    active={this.state.isLoading}
                    spinner
                    text="Loading Orders...">
                    {this.renderOrdersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('order')) {
    ReactDOM.render(<Order />, document.getElementById('order'));
}