import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class Newsletter extends Component {
    constructor(props) {
        super(props)

        this.state = {
            newsletters: {},
            isLoading: false,
        }

        this.populateNewsletters = this.populateNewsletters.bind(this)

        this.toggleLoading = this.toggleLoading.bind(this)

        this.renderNewslettersTable = this.renderNewslettersTable.bind(this)
        this.renderPagination = this.renderPagination.bind(this)
    }

    async componentWillMount() {
        await this.populateNewsletters()
    }

    async populateNewsletters(pageNumber = 1) {
        this.toggleLoading()

        await axios.get('/dashboard/newsletters/populate?page=' + pageNumber)
            .then((response) => { 
                console.log(response.data)
                this.setState({ newsletters: response.data.newsletters }) 
            })

        this.toggleLoading()
    }

    renderNewslettersTable() {
        let newsletters = this.state.newsletters
        let data = newsletters?.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((newsletter, key) => {
                            return (
                                <tr key={newsletter.id}>
                                    <td>{newsletter.title}</td>
                                    <td>{newsletter.content}</td>
                                    <td>
                                        {this.viewButton(newsletter)}
                                        {this.editButton(newsletter)}
                                        {this.deleteButton(newsletter)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    renderPagination() {
        let newsletters = this.state.newsletters

        let currentPage = newsletters?.current_page
        let perPage = newsletters?.per_page
        let total = newsletters?.total

        return newsletters ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateNewsletters.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    deleteButton(newsletter) {
        return (
            <a href={'/dashboard/newsletters/confirm_delete?id=' + newsletter.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            newsletters: searchInCollection(_.clone(this.state.allNewsletters), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.newsletters)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    activateButton(newsletter) {
        return (
            <a 
                className="btn btn-sm btn-primary" 
                href={'/dashboard/newsletters/activate?id=' + newsletter.id}>
                <i className="fas fa-check-circle mr-1"></i> Activate
            </a>
        )
    }

    viewButton(newsletter) {
        return (
            <a href={'/dashboard/newsletters/view?id=' + newsletter.id}>
                <img src="/assets/media/svg/icons/General/Visible.svg" />
            </a>
        )
    }

    editButton(newsletter) {
        return (
            <a href={'/dashboard/newsletters/edit?id=' + newsletter.id}>
                <img src="/assets/media/svg/icons/General/Edit.svg" />
            </a>
        )
    }

    deleteButton(newsletter) {
        return (
            <a href={'/dashboard/newsletters/confirm_delete?id=' + newsletter.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
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
                    text="Loading Newsletters...">
                    {this.renderNewslettersTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('newsletter')) {
    ReactDOM.render(<Newsletter />, document.getElementById('newsletter'));
}