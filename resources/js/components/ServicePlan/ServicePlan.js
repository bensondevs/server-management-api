import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Pagination from 'react-js-pagination'
import LoadingOverlay from 'react-loading-overlay';

import { searchInCollection } from '../Helper'

export default class ServicePlan extends Component {
    constructor(props) {
        super(props)

        this.state = {
            servicePlans: {},

            isLoading: false,
        }

        this.populateServicePlans = this.populateServicePlans.bind(this)

        this.toggleLoading = this.toggleLoading.bind(this)
        
        this.renderServicePlansTable = this.renderServicePlansTable.bind(this)
        this.renderPagination = this.renderPagination.bind(this)
    }

    async componentWillMount() {
        await this.populateServicePlans()
    }

    async populateServicePlans() {
        this.toggleLoading()

        await axios.get('/dashboard/service_plans/populate')
            .then((response) => {
                console.log(response.data.plans)

                this.setState({ 
                    servicePlans: response.data.plans,
                })
            })

        this.toggleLoading()
    }

    renderServicePlansTable() {
        let plans = this.state.servicePlans
        let data = plans.data

        if (data instanceof Object) data = Object.values(data)

        return (
            <div className="row">                
                {data?.map((plan, key) => {
                    return (
                        <div class="col-md-4">
                            <div class="pt-30 pt-md-25 pb-15 px-5 text-center">
                                <div class="d-flex flex-center position-relative mb-25">
                                    <span class="svg svg-fill-primary opacity-4 position-absolute">
                                        <svg width="175" height="200">
                                            <polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
                                        </svg>
                                    </span>
                                    <span class="svg-icon svg-icon-5x svg-icon-primary">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                                <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <h4 class="font-size-h3 mb-10">{plan.plan_name}</h4>
                                <div class="d-flex flex-column line-height-xl mb-10">
                                    <span>10 Domains</span>
                                    <span>Unlimited Users</span>
                                    <span>Unlimited Copies</span>
                                    <span>Free Assets</span>
                                </div>
                                <span class="font-size-h1 d-block font-weight-boldest text-dark">{plan.subscription_fee}
                                <sup class="font-size-h3 font-weight-normal pl-1">{plan.currency}</sup></span>
                                <div class="mt-7">
                                    <a 
                                        type="button"
                                        href={'/dashboard/service_plans/view?id=' + plan.id} 
                                        class="btn btn-primary text-uppercase font-weight-bolder px-15 py-3">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    )
                })}
            </div>
        )
    }

    renderPagination() {
        let servicePlans = this.state.servicePlans

        let currentPage = servicePlans.current_page
        let perPage = servicePlans.per_page
        let total = servicePlans.total

        return servicePlans ? (
            <Pagination
                activePage={currentPage}
                itemsCountPerPage={perPage}
                totalItemsCount={total}
                pageRangeDisplayed={10}
                onChange={this.populateServicePlans.bind(this)}
                activeClass="active"
                itemClass="page-item"
                linkClass="page-link"
                firstPageText="First"
                lastPageText="Last" />
        ) : null
    }

    deleteButton(servicePlan) {
        return (
            <a href={'/dashboard/service_plans/confirm_delete?id=' + servicePlan.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            servicePlans: searchInCollection(_.clone(this.state.allServicePlans), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.servicePlans)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    activateButton(servicePlan) {
        return (
            <a 
                className="btn btn-sm btn-primary" 
                href={'/dashboard/service_plans/activate?id=' + servicePlan.id}>
                <i className="fas fa-check-circle mr-1"></i> Activate
            </a>
        )
    }

    viewButton(servicePlan) {
        return (
            <a href={'/dashboard/service_plans/view?id=' + servicePlan.id}>
                <img src="/assets/media/svg/icons/General/Visible.svg" />
            </a>
        )
    }

    editButton(servicePlan) {
        return (
            <a href={'/dashboard/service_plans/edit?id=' + servicePlan.id}>
                <img src="/assets/media/svg/icons/General/Edit.svg" />
            </a>
        )
    }

    deleteButton(servicePlan) {
        return (
            <a href={'/dashboard/service_plans/confirm_delete?id=' + servicePlan.id}>
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
                    text="Loading Service Plans...">
                    {this.renderServicePlansTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {this.renderPagination()}
                </div>
            </div>
        )
    }
}

if (document.getElementById('service_plan')) {
    ReactDOM.render(<ServicePlan />, document.getElementById('service_plan'));
}