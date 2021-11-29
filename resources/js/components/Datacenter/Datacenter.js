import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { collect } from 'collect.js'
import { arrayUpdate, searchInCollection } from '../Helper'

import ReactPaginate from 'react-paginate';
import { SearchInput } from '../SearchInput';
import { PerPageOptions } from '../PerPageOptions';
import LoadingOverlay from 'react-loading-overlay';

export default class Datacenter extends Component {
    constructor(props) {
        super(props)

        this.state = {
            search: '',

            statusBadges: {},
            datacenters: {},
            
            currentPage: 1,
            offset: 0,
            perPage: 10,

            isLoading: false,

            permissions: {
                toggle: false,
                edit: false,
                delete: false,
            }
        }

        this.populateDatacenters = this.populateDatacenters.bind(this)

        this.toggleLoading = this.toggleLoading.bind(this)
        this.switchButton = this.switchButton.bind(this)
        
        this.statusBadge = this.statusBadge.bind(this)

        this.renderDatacentersTable = this.renderDatacentersTable.bind(this)
        this.renderPagination = this.renderPagination.bind(this);

        this.handleSearch = this.handleSearch.bind(this);
        this.handlePaginationClick = this.handlePaginationClick.bind(this);
        this.onPerPageOptionChange = this.onPerPageOptionChange.bind(this);
    }

    componentWillMount() {
        this.loadStatusBadges()
        this.populateDatacenters()
    }

    statusBadge(status) {
        let badges = this.state.statusBadges;
        let badge = badges[status];

        if (! badge) {
            return <span className="span span-secondary">Unknown</span>
        }
        
        return (<span className={badge.class}>{badge.content}</span>)
    }

    async populateDatacenters(pageNumber = 1) {
        this.toggleLoading()

        let url = '/dashboard/datacenters?page=' + pageNumber + '&per_page=' + this.state.perPage;

        if (this.state.search) {
            url += '&search=' + this.state.search;
        }

        await axios.get(url).then((response) => {
            let data = response.data
            let datacenters = data.datacenters

            this.setState({ datacenters: datacenters })
        })

        this.toggleLoading()
    }

    async loadStatusBadges() {
        let request = await axios.get('/meta/datacenter/status_badges');
        let badges = request.data
        
        await this.setState({ statusBadges: badges });
    }

    renderDatacentersTable() {
        let datacenters = this.state.datacenters.data

        if (! datacenters) {
            return null;
        }

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Region</th>
                    <th>Datacenter Name</th>
                    <th>Datacenter Name (for client)</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {datacenters.map((datacenter, key) => {
                            return (
                                <tr key={datacenter.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{datacenter.region_name}</td>
                                    <td>{datacenter.datacenter_name}</td>
                                    <td>
                                        {datacenter.client_datacenter_name}
                                    </td>
                                    <td>{datacenter.location}</td>
                                    <td>
                                        {this.statusBadge(datacenter.status)}
                                    </td>
                                    <td>
                                        {this.switchButton(datacenter)}
                                        
                                        {this.viewButton(datacenter)}
                                        {this.editButton(datacenter)}
                                        {this.deleteButton(datacenter)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    handlePaginationClick(data) {
        let selectedPage = data.selected;
        let offset = Math.ceil(selectedPage * this.state.perPage);

        this.setState({ offset: offset, currentPage: selectedPage }, () => {
            this.populateDatacenters(selectedPage);
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
                pageCount={this.state.datacenters.last_page}
                marginPagesDisplayed={2}
                pageRangeDisplayed={5}
                onPageChange={this.handlePaginationClick}
                containerClassName={'pagination'}
                pageClassName={'page-item'}
                pageLinkClassName={'page-link'}
                activeClassName={'active'} />
        )
    }

    switchButton(datacenter) {
        let buttonSrc = (datacenter.status != 1) ? 
            '/assets/media/svg/icons/Media/Play.svg' : 
            '/assets/media/svg/icons/Media/Pause.svg';

        return (
            <a>
                <img
                    onClick={() => this.switchDatacenterStatus(datacenter.id)} 
                    src={buttonSrc} />
            </a>
        )
    }

    async switchDatacenterStatus(id) {
        this.toggleLoading()

        await axios.post('/dashboard/datacenters/' + id + '/switch_status').then((response) => {
            this.populateDatacenters();
        }).catch((error) => console.log(error.response.data))
        
        this.toggleLoading()
    }

    viewButton(datacenter) {
        return (
            <a href={'/dashboard/datacenters/' + datacenter.id + '/subnets'}>
                <img src="/assets/media/svg/icons/General/Visible.svg"/>
            </a>
        )
    }

    editButton(datacenter) {
        let permissions = this.state.permissions

        if (permissions.edit) {
            return (
                <a href={'/dashboard/datacenters/' + datacenter.id + '/edit'}>
                    <img src="/assets/media/svg/icons/General/Edit.svg" />
                </a>
            )
        }
    }

    deleteButton(datacenter) {
        let permissions = this.state.permissions

        if (permissions.delete) {
            return (
                <a href={'/dashboard/datacenters/' + datacenter.id + '/delete'}>
                    <img src="/assets/media/svg/icons/Home/Trash.svg" />
                </a>
            )
        }
    }

    async handleSearch(keyword) {
        await this.setState({ search: keyword });
        await this.populateDatacenters();
    }

    async onPerPageOptionChange(event) {
        let perPage = event.target.value;

        await this.setState({ perPage: perPage });
        await this.populateDatacenters();
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
                    text="Loading datacenters...">
                    {this.renderDatacentersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('datacenter')) {
    ReactDOM.render(<Datacenter />, document.getElementById('datacenter'));
}