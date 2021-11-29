import axios from 'axios';
import React, { Component, useEffect } from 'react';
import ReactDOM from 'react-dom';
import ReactPaginate from 'react-paginate';

import { searchInCollection, arrayUpdate } from '../Helper'

import LoadingOverlay from 'react-loading-overlay';
import { PerPageOptions } from '../PerPageOptions';
import { SearchInput } from '../SearchInput';

export default class Payment extends Component {
    constructor(props) {
        super(props)

        this.state = {
            payments: {},
            badges: [],
            isLoading: false,

            currentPage: 1,
            offset: 0,
            perPage: 10,

            search: '',
            isTyping: false,
            typingTimeout: 0,
        }

        this.populatePayments = this.populatePayments.bind(this);

        this.toggleLoading = this.toggleLoading.bind(this);

        this.renderPagination = this.renderPagination.bind(this);
        this.renderPerPageOptions = this.renderPerPageOptions.bind(this);
        this.renderPaymentsTable = this.renderPaymentsTable.bind(this);

        this.handleSearch = this.handleSearch.bind(this);
        this.handlePaginationClick = this.handlePaginationClick.bind(this);
        this.onPerPageOptionChange = this.onPerPageOptionChange.bind(this);
    }

    async componentWillMount() {
        await this.loadStatusBadges();
        await this.populatePayments(this.state.currentPage, '');
    }

    async populatePayments(pageNumber = 1) {
        this.toggleLoading()

        let url = `/dashboard/payments?per_page=${this.state.perPage}&page=${pageNumber}`;
        if (this.state.search) {
            url += '&search=' + encodeURI(this.state.search);
        }

        await axios.get(url).then((response) => {
            let payments = response.data.payments;
            this.setState({ payments: payments });
        }).catch((error) => { console.log(error.response.data); })

        this.toggleLoading()
    }

    async loadStatusBadges() {
        let request = await axios.get('/meta/payment/status_badges');
        let badges = request.data

        this.setState({ badges: badges });
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

    renderPaymentsTable() {
        let payments = this.state.payments
        let data = payments?.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Payer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((payment, key) => {
                            return (
                                <tr key={payment.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{this.userLink(payment)}</td>
                                    <td>{payment.payment_method}</td>
                                    <td>{payment.amount}</td>
                                    <td>{this.statusLabel(payment)}</td>
                                    <td>{payment.created_at}</td>
                                    <td>
                                        {this.viewButton(payment)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
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
                pageCount={this.state.payments.last_page}
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
            this.populatePayments(selectedPage);
        });
    }

    deleteButton(payment) {
        return (
            <a href={'/dashboard/payments/' + payment.id + '/delete'}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        );
    }

    switchButton(payment) {
        let buttonSrc = (payment.status != 1) ? 
            '/assets/media/svg/icons/Media/Play.svg' : 
            '/assets/media/svg/icons/Media/Pause.svg';

        return (
            <a>
                <img
                    onClick={() => this.toggleDatacenter(payment.id)} 
                    src={buttonSrc} />
            </a>
        )
    }

    async switch(payment) {
        let url = '/dashboard/payments/switch_status'

        this.toggleLoading()
        await axios.patch(url, { id: payment.id }).then((response) => {
            let updatedSubnet = response.data.payment;
            let payments = this.state.payments;
            payments.data = arrayUpdate(payments.data, updatedSubnet);

            this.setState({ payments: payments });
        })
        this.toggleLoading()
    }

    async handleSearch(keyword) {
        await this.setState({ search: keyword });
        await this.populatePayments();
    }

    async onPerPageOptionChange(event) {
        let perPage = event.target.value;

        await this.setState({ perPage: perPage });
        await this.populatePayments();
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

    render() {
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
                    text="Loading payments...">
                    {this.renderPaymentsTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('payment')) {
    ReactDOM.render(<Payment />, document.getElementById('payment'));
}