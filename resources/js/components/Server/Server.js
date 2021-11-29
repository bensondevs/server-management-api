import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { collect } from 'collect.js'
import { searchInCollection } from '../Helper'

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class Server extends Component {
    constructor(props) {
        super(props)

        this.state = {
            servers: {},
            isLoading: false,

            permissions: {
                toggle: false,
                edit: false,
                delete: false,
            }
        }

        this.populateServers = this.populateServers.bind(this)
        this.toggleLoading = this.toggleLoading.bind(this)

        this.serverContainersButton = this.serverContainersButton.bind(this)
    }

    componentDidMount() {
        this.populateServers()
        this.checkPermissions()
    }

    async populateServers() {
        this.toggleLoading()

        await axios.get('/dashboard/servers/populate')
            .then((response) => {
                this.setState({ 
                    servers: response.data.servers,
                })
            })

        this.toggleLoading()
    }

    renderServersTable() {
        let servers = this.state.servers
        let data = servers.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Server Name</th>
                    <th>IP Address</th>
                    <th>Datacenter</th>
                    <th>Containers</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((server, key) => {
                            return (
                                <tr key={server.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{server.server_name}</td>
                                    <td>
                                        {server.ip_address}
                                    </td>
                                    <td>{server.datacenter}</td>
                                    <td>{this.serverContainersButton(server)}</td>
                                    <td>
                                        <span className={'label label-' + (server.status == 'active' ? 'success' : 'danger')  + ' label-inline font-weight-lighter mr-2'}>
                                            {server.status == 'active' ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td>
                                        {this.toggleButton(server)}
                                        {this.editButton(server)}
                                        {this.deleteButton(server)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    renderPagination() {
        let servers = this.state.servers

        let currentPage = servers.current_page
        let perPage = servers.per_page
        let total = servers.total

        return servers ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateServers.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    async checkPermissions() {
        let checkedPermissions = [
            'toggle servers',
            'edit servers',
            'delete servers',
        ]

        await axios.get('/dashboard/permissions/check_permissions?permissions=' + JSON.stringify(checkedPermissions))
            .then((response) => {
                let _permissions = response.data.permissions
                let permissions = _.clone(this.state.permissions)
                permissions.toggle = _permissions['toggle servers']
                permissions.edit = _permissions['edit servers']
                permissions.delete = _permissions['delete servers']

                this.setState({ permissions: permissions }, () => console.log(this.state.permissions))
            }).catch((error) => console.log(error.repsonse.data))
    }

    toggleButton(server) {
        let permissions = this.state.permissions

        if (permissions.toggle) {
            return (
                <a>
                    <img
                        onClick={() => this.toggleServer(server.id)} 
                        src={(server.status != 'active') ?  '/assets/media/svg/icons/Media/Play.svg' : '/assets/media/svg/icons/Media/Pause.svg'} />
                </a>
            )
        }

        return null
    }

    editButton(server) {
        let permissions = this.state.permissions

        if (permissions.edit) {
            return (
                <a href={'/dashboard/servers/edit?id=' + server.id}>
                    <img src="/assets/media/svg/icons/General/Edit.svg" />
                </a>
            )
        }
    }

    deleteButton(server) {
        let permissions = this.state.permissions

        if (permissions.delete) {
            return (
                <a href={'/dashboard/servers/confirm_delete?id=' + server.id}>
                    <img src="/assets/media/svg/icons/Home/Trash.svg" />
                </a>
            )
        }
    }

    async toggleServer(id) {
        this.toggleLoading()

        await axios.post('/dashboard/servers/toggle_status', { id: id })
            .then((response) => {
                let updatedServer = response.data.data

                let servers = collect(_.clone(this.state.allServers))
                let updatedIndex = servers.search((server) => {
                    return server.id == id
                })
                servers = servers.items
                servers[updatedIndex].status = updatedServer.status

                let currentPage = this.paginationRef.state.pager.currentPage
                this.setState({ servers: servers })
                this.paginationRef.setPage(currentPage)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            servers: searchInCollection(_.clone(this.state.allServers), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.servers)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    serverContainersButton(server) {
        return (
            <a 
                className={'btn btn-sm btn-primary ' + (server.total_containers ? '' : 'disabled')}
                href={'/dashboard/servers/containers?id=' + server.id}>
                <i className="fas fa-folder mr-1"></i> Containers {'(' + server.total_containers + ')'}
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
                    text="Loading servers...">
                    {this.renderServersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('server')) {
    ReactDOM.render(<Server />, document.getElementById('server'));
}