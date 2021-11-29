import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { searchInCollection } from '../Helper'

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class Administrator extends Component {
    constructor(props) {
        super(props)

        this.state = {
            administrators: {},

            permissions: {
                edit: false,
                delete: false,
            }
        }

        this.populateAdministrators = this.populateAdministrators.bind(this)

        this.toggleLoading = this.toggleLoading.bind(this)

        this.renderAdministratorsTable = this.renderAdministratorsTable.bind(this)
        this.renderPagination = this.renderPagination.bind(this)
    }

    async componentWillMount() {
        await this.populateAdministrators()
    }

    async populateAdministrators(pageNumber = 1) {
        this.toggleLoading()

        await axios.get('/dashboard/administrators/populate?page=' + pageNumber)
            .then((response) => {
                this.setState({ 
                    administrators: response.data.administrators 
                })
            })

        this.toggleLoading()
    }

    renderAdministratorsTable() {
        let administrators = this.state.administrators
        let data = administrators?.data

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
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((administrator, key) => {
                            return (
                                <tr key={administrator.id}>
                                    <td>{(key + 1)}</td>
                                    <td>{administrator.full_name}</td>
                                    <td>{administrator.email}</td>
                                    <td>{administrator.username}</td>
                                    <td>{administrator.company_name}</td>
                                    <td>
                                        <a href={'/dashboard/administrators/view?id=' + administrator.id}>
                                            <img src="/assets/media/svg/icons/General/Visible.svg"/>
                                        </a>
                                        <a href={'/dashboard/administrators/demote_administrator?id=' + administrator.id}>
                                            <img src="/assets/media/svg/icons/Home/Trash.svg"/>
                                        </a>
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    renderPagination() {
        let administrators = this.state.administrators

        let currentPage = administrators.current_page
        let perPage = administrators.per_page
        let total = administrators.total

        return administrators ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateAdministrators.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            administrators: searchInCollection(_.clone(this.state.allAdministrators), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.administrators)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) }, () => console.log(this.state.isLoading))
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
                    text="Loading administrators...">
                    {this.renderAdministratorsTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('administrator')) {
    ReactDOM.render(<Administrator />, document.getElementById('administrator'));
}