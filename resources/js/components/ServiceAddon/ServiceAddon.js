import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

import { searchInCollection } from '../Helper'

export default class ServiceAddon extends Component {
    constructor(props) {
        super(props)

        this.state = {
            serviceAddons: [],
            isLoading: false,
        }

        this.populateServiceAddons = this.populateServiceAddons.bind(this)

        this.toggleLoading = this.toggleLoading.bind(this)
    }

    async componentWillMount() {
        await this.populateServiceAddons()
    }

    async populateServiceAddons(pageNumber = 1) {
        this.toggleLoading()

        await axios.get('/dashboard/service_addons/populate?page=' + pageNumber)
            .then((response) => {
                this.setState({ serviceAddons: response.data.addons })
            })

        this.toggleLoading()
    }

    renderServiceAddonsTable() {
        let serviceAddons = this.state.serviceAddons
        let data = serviceAddons?.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <table className="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Addon Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                        {data?.map((serviceAddon, key) => {
                            return (
                                <tr key={serviceAddon.id}>
                                    <td>{serviceAddon.addon_name}</td>
                                    <td>{serviceAddon.addon_type}</td>
                                    <td>{serviceAddon.currency} {serviceAddon.addon_fee}</td>
                                    <td>{serviceAddon.quantity}</td>
                                    <td>{serviceAddon.unit}</td>
                                    <td>{serviceAddon.description}</td>
                                    <td>
                                        {this.viewButton(serviceAddon)}
                                        {this.editButton(serviceAddon)}
                                        {this.deleteButton(serviceAddon)}
                                    </td>
                                </tr>
                            )
                        })}
                </tbody>
            </table>
        )
    }

    renderPagination() {
        let serviceAddons = this.state.serviceAddons

        let currentPage = serviceAddons.current_page
        let perPage = serviceAddons.per_page
        let total = serviceAddons.total

        return serviceAddons ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateServiceAddons.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    deleteButton(serviceAddon) {
        return (
            <a href={'/dashboard/service_addons/confirm_delete?id=' + serviceAddon.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            serviceAddons: searchInCollection(_.clone(this.state.allServiceAddons), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.serviceAddons)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    activateButton(serviceAddon) {
        return (
            <a 
                className="btn btn-sm btn-primary" 
                href={'/dashboard/service_addons/activate?id=' + serviceAddon.id}>
                <i className="fas fa-check-circle mr-1"></i> Activate
            </a>
        )
    }

    viewButton(serviceAddon) {
        return (
            <a href={'/dashboard/service_addons/view?id=' + serviceAddon.id}>
                <img src="/assets/media/svg/icons/General/Visible.svg" />
            </a>
        )
    }

    editButton(serviceAddon) {
        return (
            <a href={'/dashboard/service_addons/edit?id=' + serviceAddon.id}>
                <img src="/assets/media/svg/icons/General/Edit.svg" />
            </a>
        )
    }

    deleteButton(serviceAddon) {
        return (
            <a href={'/dashboard/service_addons/confirm_delete?id=' + serviceAddon.id}>
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
                    text="Loading Service Addons...">
                    {this.renderServiceAddonsTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('service_addon')) {
    ReactDOM.render(<ServiceAddon />, document.getElementById('service_addon'));
}