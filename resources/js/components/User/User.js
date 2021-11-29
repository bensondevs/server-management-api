import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { searchInCollection } from '../Helper'

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class User extends Component {
    constructor(props) {
        super(props)

        this.state = {
            users: {},
            isLoading: false,

            permissions: {
                edit: false,
                delete: false,
            }
        }

        this.populateUsers = this.populateUsers.bind(this)
        this.toggleLoading = this.toggleLoading.bind(this)
    }

    async componentWillMount() {
        await this.checkPermissions()
        await this.populateUsers()
    }

    async populateUsers(pageNumber = 1) {
        this.toggleLoading()

        await axios.get('/dashboard/users/populate?page=' + pageNumber)
            .then((response) => {
                this.setState({ users: response.data.users })
            })

        this.toggleLoading()
    }

    renderUsersTable() {
        let users = this.state.users
        let data = users?.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Company Name</th>
                    <th>Promote</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((user, key) => {
                            return (
                                <tr key={user.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{user.full_name}</td>
                                    <td>{user.email}</td>
                                    <td>{user.username}</td>
                                    <td>{user.company_name}</td>
                                    <td>
                                        <button className="btn btn-success btn-sm">
                                            <i className="fas fa-user-shield mr-1"></i> Promote To Administrator
                                        </button>
                                    </td>
                                    <td>
                                        <a href={'/dashboard/users/view?id=' + user.id}>
                                            <img src="/assets/media/svg/icons/General/Visible.svg"/>
                                        </a>
                                        {this.deleteButton(user)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    renderPagination() {
        let users = this.state.users

        let currentPage = users.current_page
        let perPage = users.per_page
        let total = users.total

        return users ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateUsers.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    async checkPermissions() {
        let checkedPermissions = [
            'edit users',
            'delete users',
        ]

        await axios.get('/dashboard/permissions/check_permissions?permissions=' + JSON.stringify(checkedPermissions))
            .then((response) => {
                let _permissions = response.data.permissions
                let permissions = _.clone(this.state.permissions)
                permissions.edit = _permissions['edit users']
                permissions.delete = _permissions['delete users']

                this.setState({ permissions: permissions })
            }).catch((error) => console.log(error.response.data))
    }

    deleteButton(user) {
        let permissions = this.state.permissions

        if (permissions.delete) {
            return (
                <a href={'/dashboard/users/confirm_delete?id=' + user.id}>
                    <img src="/assets/media/svg/icons/Home/Trash.svg" />
                </a>
            )
        }
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            users: searchInCollection(_.clone(this.state.allUsers), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.users)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) }, () => console.log(this.state.isLoading))
    }

    onChangePage(shownUsers, startIndex) {
        this.setState({
            shownUsers: shownUsers,
            startIndex: startIndex
        })
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
                    text="Loading users...">
                    {this.renderUsersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('user')) {
    ReactDOM.render(<User />, document.getElementById('user'));
}