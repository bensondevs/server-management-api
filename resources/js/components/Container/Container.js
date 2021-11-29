import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import ReactPaginate from 'react-paginate';

import LoadingOverlay from 'react-loading-overlay';
import { PerPageOptions } from '../PerPageOptions';
import { SearchInput } from '../SearchInput';

export default class Container extends Component {
    constructor(props) {
        super(props)

        this.state = {
            containers: {},
            badges: [],
            isLoading: false,

            currentPage: 1,
            offset: 0,
            perPage: 10,

            search: '',
            isTyping: false,
            typingTimeout: 0,
        }

        this.populateContainers = this.populateContainers.bind(this);
        this.loadStatusBadges = this.loadStatusBadges.bind(this);

        this.toggleLoading = this.toggleLoading.bind(this);

        this.renderDropdownActionButtons = this.renderDropdownActionButtons.bind(this);
        this.renderContainersTable = this.renderContainersTable.bind(this);
        this.renderPagination = this.renderPagination.bind(this);
        this.renderPerPageOptions = this.renderPerPageOptions.bind(this);
        this.statusBadge = this.statusBadge.bind(this);

        this.handleSearch = this.handleSearch.bind(this);
        this.handlePaginationClick = this.handlePaginationClick.bind(this);
        this.onPerPageOptionChange = this.onPerPageOptionChange.bind(this);
    }

    async componentWillMount() {
        await this.loadStatusBadges();
        await this.populateContainers();
    }

    async populateContainers(pageNumber = 1) {
        this.toggleLoading();

        let url = `/dashboard/containers?per_page=${this.state.perPage}&page=${pageNumber}`;
        if (this.state.search) {
            url += '&search=' + encodeURI(this.state.search);
        }

        await axios.get(url).then((response) => { 
            this.setState({ containers: response.data.containers });
        })

        this.toggleLoading()
    }

    async loadStatusBadges() {
        let request = await axios.get('/meta/container/status_badges');
        let badges = request.data

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

    renderDropdownActionButtons(container) {
        return (
            <div className="dropdown">
                <button 
                    className="btn btn-light dropdown-toggle" 
                    type="button" 
                    id="dropdownMenuButton" 
                    data-toggle="dropdown" 
                    aria-haspopup="true" 
                    aria-expanded="false">
                    Actions
                </button>
                <div className="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a className="dropdown-item" href={'/dashboard/containers/' + container.id + '/view'}>
                        View Services
                    </a>
                    <a className="dropdown-item" href={'/dashboard/containers/' + container.id + '/command_executions'}>
                        Command Executions
                    </a>
                    <a className="dropdown-item text-danger" href={'/dashboard/containers/' + container.id + '/delete'}>
                        Delete
                    </a>
                </div>
            </div>
        );
    }

    renderContainersTable() {
        let containers = this.state.containers
        let data = containers?.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Server Name</th>
                        <th>Status</th>
                        <th>Service Plan</th>
                        <th>IP Address</th>
                        <th>Hostname</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Activation Date</th>
                        <th>Expiration Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        {data?.map((container, key) => {
                            return (
                                <tr key={container.id}>
                                    <td>{container.id}</td>
                                    <td>
                                        <a href={'/dashboard/servers/containers?id=' + container.server_id}>
                                            {container.server_name}
                                        </a>
                                    </td>
                                    <td>{this.statusBadge(container.status)}</td>
                                    <td>{container.service_plan.plan_name}</td>
                                    <td>{container.ip_address}</td>
                                    <td>{container.hostname}</td>
                                    <td>{container.customer_name}</td>
                                    <td>{container.order_date}</td>
                                    <td>{container.activation_date}</td>
                                    <td>{container.expiration_date}</td>
                                    <td>
                                        {this.renderDropdownActionButtons(container)}
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
                pageCount={this.state.containers.last_page}
                marginPagesDisplayed={2}
                pageRangeDisplayed={5}
                onPageChange={this.handlePaginationClick}
                containerClassName={'pagination'}
                pageClassName={'page-item'}
                pageLinkClassName={'page-link'}
                activeClassName={'active'} />
        );
    }

    handlePaginationClick(data) {
        let selectedPage = data.selected;
        let offset = Math.ceil(selectedPage * this.state.perPage);

        console.log(selectedPage);

        this.setState({ offset: offset, currentPage: selectedPage }, () => {
            this.populateSubnets(selectedPage);
        });
    }

    async handleSearch(keyword) {
        await this.setState({ search: keyword });
        await this.populateSubnets();
    }

    async onPerPageOptionChange(event) {
        let perPage = event.target.value;

        await this.setState({ perPage: perPage });
        await this.populateSubnets();
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
                    text="Loading Containers...">
                    {this.renderContainersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('container')) {
    ReactDOM.render(<Container />, document.getElementById('container'));
}