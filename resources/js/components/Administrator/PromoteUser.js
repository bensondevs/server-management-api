import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Pagination from '../Pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class PromoteUser extends Component {
    constructor(props) {
        super(props)

        this.state = {
            allUsers: [],
            users: [],
            shownUsers: [],
            startIndex: 1,
            isLoading: false,
        }

        this.populateUsers = this.populateUsers.bind(this)

        this.onChangePage = this.onChangePage.bind(this)
        this.toggleLoading = this.toggleLoading.bind(this)
    }

    componentDidMount() {
        this.populateUsers()
    }

    populateUsers() {
        let rawUsers = document.getElementById('promote_user').getAttribute('data-users')
        let users = JSON.parse(rawUsers)
        this.setState({ 
            users: users,
            allUsers: users,
        })
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
                    <table className="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Company Name</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                                {this.state.shownUsers.map((user, key) => {
                                    return (
                                        <tr key={user.id}>
                                            <td>{(key + 1)}</td>
                                            <td>{user.full_name}</td>
                                            <td>{user.email}</td>
                                            <td>{user.username}</td>
                                            <td>{user.company_name}</td>
                                            <td>{user.balance}</td>
                                            <td>
                                                <a href={'/dashboard/users/view?id=' + user.id}>
                                                    <img src="/assets/media/svg/icons/General/Visible.svg"/>
                                                </a>
                                            </td>
                                        </tr>
                                    )
                                })}
                        </tbody>
                    </table>
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    <Pagination 
                        items={this.state.users ? this.state.users : []} 
                        onChangePage={this.onChangePage}
                        ref={(ref) => this.paginationRef = ref} />
                </div>
            </div>
        )
    }
}

if (document.getElementById('promote_user')) {
    ReactDOM.render(<PromoteUser />, document.getElementById('promote_user'));
}