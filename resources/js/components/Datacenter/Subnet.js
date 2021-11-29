import axios from 'axios';
import React, { Component, useEffect } from 'react';
import ReactDOM from 'react-dom';
import ReactPaginate from 'react-paginate';

import { searchInCollection, arrayUpdate } from '../Helper'

import LoadingOverlay from 'react-loading-overlay';
import { PerPageOptions } from '../PerPageOptions';
import { SearchInput } from '../SearchInput';

export default class Subnet extends Component {
    constructor(props) {
        super(props)

        this.state = {
            subnets: {},
            badges: [],
            isLoading: false,

            currentPage: 1,
            offset: 0,
            perPage: 10,

            search: '',
            isTyping: false,
            typingTimeout: 0,

            permissions: {
                edit: false,
                delete: false,
            }
        }

        this.populateSubnets = this.populateSubnets.bind(this);

        this.toggleLoading = this.toggleLoading.bind(this);

        this.renderPagination = this.renderPagination.bind(this);
        this.renderPerPageOptions = this.renderPerPageOptions.bind(this);

        this.handleSearch = this.handleSearch.bind(this);
        this.handlePaginationClick = this.handlePaginationClick.bind(this);
        this.onPerPageOptionChange = this.onPerPageOptionChange.bind(this);
    }

    async componentWillMount() {
        await this.loadStatusBadges();
        await this.populateSubnets(this.state.currentPage, '');
    }

    async populateSubnets(pageNumber = 1) {
        this.toggleLoading()

        let datacenterComponent = document.getElementById('datacenter_subnet')
        let id = datacenterComponent.getAttribute('data-datacenter-id')

        let url = `/dashboard/datacenters/${id}/subnets?per_page=${this.state.perPage}&page=${pageNumber}`;
        if (this.state.search) {
            url += '&search=' + encodeURI(this.state.search);
        }

        await axios.get(url).then((response) => {
            let subnets = response.data.subnets;
            this.setState({ subnets: subnets });
        }).catch((error) => { console.log(error.response.data); })

        this.toggleLoading()
    }

    async loadStatusBadges() {
        let request = await axios.get('/meta/subnet/status_badges');
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

    renderSubnetsTable() {
        let subnets = this.state.subnets
        let data = subnets.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Datacenter</th>
                    <th>Subnet Mask</th>
                    <th>Status</th>
                    <th>Available IP(s)</th>
                    <th>Total IP(s)</th>
                    <th>Toggle</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((subnet, key) => {
                            return (
                                <tr key={subnet.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{subnet.datacenter_name}</td>
                                    <td>
                                        {subnet.subnet_mask}
                                    </td>
                                    <td>
                                        {this.statusBadge(subnet.status)}
                                    </td>
                                    <td>{subnet.total_available_ips}</td>
                                    <td>{subnet.total_ips}</td>
                                    <td>{this.switchButton(subnet)}</td>
                                    <td>
                                        <a href={'/dashboard/subnets/' + subnet.id + '/ips'}>
                                            <img src="/assets/media/svg/icons/General/Visible.svg"/>
                                        </a>
                                        {this.deleteButton(subnet)}
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
                pageCount={this.state.subnets.last_page}
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
        let selectedPage = data.selected;
        let offset = Math.ceil(selectedPage * this.state.perPage);

        console.log(selectedPage);

        this.setState({ offset: offset, currentPage: selectedPage }, () => {
            this.populateSubnets(selectedPage);
        });
    }

    deleteButton(subnet) {
        return (
            <a href={'/dashboard/subnets/' + subnet.id + '/delete'}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        );
    }

    switchButton(subnet) {
        let buttonSrc = (subnet.status != 1) ? 
            '/assets/media/svg/icons/Media/Play.svg' : 
            '/assets/media/svg/icons/Media/Pause.svg';

        return (
            <a>
                <img
                    onClick={() => this.toggleDatacenter(subnet.id)} 
                    src={buttonSrc} />
            </a>
        )
    }

    async switch(subnet) {
        let url = '/dashboard/subnets/switch_status'

        this.toggleLoading()
        await axios.patch(url, { id: subnet.id }).then((response) => {
            let updatedSubnet = response.data.subnet;
            let subnets = this.state.subnets;
            subnets.data = arrayUpdate(subnets.data, updatedSubnet);

            this.setState({ subnets: subnets });
        })
        this.toggleLoading()
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
                    text="Loading subnets...">
                    {this.renderSubnetsTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('datacenter_subnet')) {
    ReactDOM.render(<Subnet />, document.getElementById('datacenter_subnet'));
}