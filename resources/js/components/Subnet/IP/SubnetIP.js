import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { searchInCollection, arrayUpdate } from '../../Helper'

import ReactPaginate from 'react-paginate';
import { SearchInput } from '../../SearchInput';
import { PerPageOptions } from '../../PerPageOptions';
import LoadingOverlay from 'react-loading-overlay';

export default class SubnetIp extends Component {
    constructor(props) {
        super(props)

        this.state = {
            subnet: {},

            currentPage: 1,
            offset: 0,
            perPage: 10,

            search: '',
            isTyping: false,
            typingTimeout: 0,

            ips: [],
            isLoading: false,

            permissions: {
                edit: false,
                delete: false,
            }
        }

        this.populateIps = this.populateIps.bind(this);
        this.switchForbidden = this.switchForbidden.bind(this);

        this.toggleLoading = this.toggleLoading.bind(this);

        this.renderSubnetIpsTable = this.renderSubnetIpsTable.bind(this);
        this.renderPagination = this.renderPagination.bind(this);
        this.renderPerPageOptions = this.renderPerPageOptions.bind(this);

        this.userAnchor = this.userAnchor.bind(this);
        this.switchForbiddenButton = this.switchForbiddenButton.bind(this);

        this.handleSearch = this.handleSearch.bind(this);
        this.handlePaginationClick = this.handlePaginationClick.bind(this);
        this.onPerPageOptionChange = this.onPerPageOptionChange.bind(this);
    }

    async componentWillMount() {
        await this.populateIps(1)
    }

    async populateIps(pageNumber = 1) {
        let rawSubnet = document.getElementById('subnet_ip').getAttribute('data-subnet');
        let subnet = JSON.parse(rawSubnet);

        let url = `/dashboard/subnets/${subnet.id}/ips?per_page=${this.state.perPage}&page=${pageNumber}`;
        if (this.state.search) {
            url += '&search=' + encodeURI(this.state.search);
        }

        await axios.get(url).then((response) => {
            let ips = response.data.subnet_ips;
            this.setState({ ips: ips });
        }).catch((error) => console.log(error.response.data));
    }

    renderSubnetIpsTable() {
        let ips = this.state.ips.data
        if (! ips) return null;

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>IP Address</th>
                    <th>Assigned User</th>
                    <th>Comment</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {ips.map((ip, key) => {
                            return (
                                <tr key={ip.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{ip.ip_address}</td>
                                    <td>{ip.user}</td>
                                    <td>{ip.comment}</td>
                                    <td>
                                        {this.editButton(ip)}
                                        {this.deleteButton(ip)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    async switchForbidden(ip) {
        var url = '/dashboard/subnets/' + ip.subnet_id + '/ips/' + ip.id + '/switch_forbidden'

        this.toggleLoading()
        await axios.post(url, { id: ip.id }).then((response) => {
            let ips = this.state.ips;
            ips.data = arrayUpdate(ips.data, response.data.subnet_ip);
        }).catch((error) => {
            console.log(error.response.data)
        })
        this.toggleLoading()
    }

    async handlePaginationClick(data) {
        let selectedPage = data.selected + 1;
        let offset = Math.ceil(selectedPage * this.state.perPage);

        await this.setState({ offset: offset, currentPage: selectedPage }, async () => {
            console.log(this.state.currentPage);
            await this.populateIps(selectedPage);
        });
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
                pageCount={this.state.ips.last_page}
                marginPagesDisplayed={2}
                pageRangeDisplayed={5}
                onPageChange={this.handlePaginationClick}
                containerClassName={'pagination'}
                pageClassName={'page-item'}
                pageLinkClassName={'page-link'}
                activeClassName={'active'} />
        )
    }

    switchForbiddenButton(ip) {
        return ip.is_forbidden ?
            (
                <button 
                    onClick={() => this.switchForbidden(ip)} 
                    className="btn btn-sm btn-success">
                    <i className="fas fa-check mr-1"></i> Allow
                </button>
            ) :
            (
                <button 
                    onClick={() => this.switchForbidden(ip)} 
                    className="btn btn-sm btn-danger">
                    <i className="fas fa-times mr-1"></i> Forbid
                </button>
            )
    }

    editButton(ip) {
        return (
            <a href={'/dashboard/subnets/' + ip.subnet_id + 'ips/edit?id=' + ip.id}>
                <img src="/assets/media/svg/icons/General/Edit.svg" />
            </a>
        )
    }

    deleteButton(ip) {
        return (
            <a href={'/dashboard/subnets/' + ip.subnet_id + 'ips/confirm_delete?id=' + ip.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    async handleSearch(keyword) {
        await this.setState({ search: keyword });
        await this.populateIps();
    }

    async onPerPageOptionChange(event) {
        let perPage = event.target.value;

        await this.setState({ perPage: perPage });
        await this.populateIps();
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

    userAnchor(user) {
        return (
            <a href={'/dashboard/users/view?id=' + user.id}>
                {user.full_name}
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
                    text="Loading ips...">
                    {this.renderSubnetIpsTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('subnet_ip')) {
    ReactDOM.render(<SubnetIp />, document.getElementById('subnet_ip'));
}