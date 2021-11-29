import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { arrayUpdate, arrayFindIndex, arrayPluck } from '../../Helper'

import LoadingOverlay from 'react-loading-overlay';

export default class OrderForm extends Component {
    constructor(props) {
        super(props)

        this.state = {
            isServicePlansLoading: false,
            isServiceAddonsLoading: false,
            isDatacentersLoading: false,
            isCustomersLoading: false,
            isStatusesLoading: false,

            servicePlans: [],
            serviceAddons: [],
            datacenters: [],
            customers: [],
            statuses: [],

            addedAddons: [],
        }

        this.loadServicePlans = this.loadServicePlans.bind(this)
        this.loadDatacenters = this.loadDatacenters.bind(this)
        this.loadCustomers = this.loadCustomers.bind(this)
        this.loadStatuses = this.loadStatuses.bind(this)

        this.updateAddedAddons = this.updateAddedAddons.bind(this)
        this.changeAddonQuantity = this.changeAddonQuantity.bind(this)
        this.updateAddedAddons = this.updateAddedAddons.bind(this)
    }

    componentDidMount() {
        // Load Service Plans
        this.loadServicePlans()

        // Load Service Addons
        // this.loadServiceAddons()

        // Load Datacenters
        this.loadDatacenters()

        // Load Customers
        this.loadCustomers()

        // Load Available Statuses
        this.loadStatuses()
    }

    updateAddedAddons(addon) {
        let addedAddons = this.state.addedAddons
        let newAddedAddon = {
            service_addon_id: addon.id,
            quantity: addon.added_quantity ? addon.added_quantity : 0,
        }
        
        let updatedAddedAddons = []
        let updateOrPluck = (addon.added_quantity > 0)
        updatedAddedAddons = updateOrPluck ? 
            arrayUpdate(
                addedAddons, 
                newAddedAddon, 
                'service_addon_id', 
                'service_addon_id'
            ) :
            arrayPluck(
                addedAddons, 
                newAddedAddon, 
                'service_addon_id', 
                'service_addon_id'
            )
        
        // Update current state and parent state
        this.setState({ addedAddons: updatedAddedAddons })
        this.props.handleAddonChange(this.state.addedAddons)
    }

    changeAddonQuantity(addon, newQuantity = 0) {
        let selectedAddon = addon
        selectedAddon.added_quantity = (newQuantity >= 0) ? newQuantity : 0
        let addons = arrayUpdate(this.state.serviceAddons, selectedAddon)
        this.setState({ serviceAddons: addons })

        this.updateAddedAddons(selectedAddon)
    }

    setAddonQuantity(event, addon) {
        const target = event.target
        const value = parseInt(target.value)

        // Prepare addon item with new quantity
        let newQuantity = (value >= 0 ? value : 0)
        let selectedAddon = addon
        selectedAddon.added_quantity = newQuantity

        // Update the while array of items
        let addons = this.state.serviceAddons
        addons = arrayUpdate(addons, selectedAddon)
        this.setState({ serviceAddons: addons })

        this.updateAddedAddons(selectedAddon)
    }

    async loadServicePlans() {
        await axios.get('/dashboard/service_plans/')
            .then((response) => {
                let plans = response.data.plans.data
                this.setState({ servicePlans: plans }, () => console.log(this.state.servicePlans))
                this.props.setInputValue('service_plan_id', plans[0].id)
            }).catch((error) => console.log(error.response))
    }

    async loadServiceAddons() {
        this.setState({ isServiceAddonsLoading: true })

        await axios.get('/dashboard/service_addons/')
            .then((response) => {
                this.setState({ serviceAddons: response.data.addons })
            }).catch((error) => console.log(error.response))

        this.setState({ isServiceAddonsLoading: false })
    }

    async loadDatacenters() {
        await axios.get('/dashboard/datacenters/')
            .then((response) => {
                let datacenters = response.data.datacenters.data
                this.setState({ datacenters: datacenters })
                this.props.setInputValue('datacenter_id', datacenters[0].id)
            })
    }

    async loadCustomers() {
        this.setState({ isCustomersLoading: true })

        await axios.get('/dashboard/users/')
            .then((response) => {
                let customers = response.data.users.data
                this.setState({ customers: customers })
                this.props.setInputValue('customer_id', customers[0].id)
            }).catch((error) => console.log(error.response))
        
        this.setState({ isCustomersLoading: false })
    }

    async loadStatuses() {
        this.setState({ isStatusesLoading: true })

        await axios.get('/meta/order/statuses')
            .then((response) => {
                let statuses = response.data.statuses;
                this.setState({ statuses: statuses })
                this.props.setInputValue('status', 1)
            }).catch((error) => console.log(error.response.data))
        
        this.setState({ isStatusesLoading: false })
    }

    render() {
        let input = this.props.input

        return (
            <div>
                <div className="bg-light">
                    <div className="separator separator-dashed my-8"></div>
                    <h4 className="ml-2">Order Information</h4>
                    <div className="separator separator-dashed my-8"></div>
                </div>

                <div className="form-group">
                    <label>Datacenter</label>
                    <div className="input-group">
                        <LoadingOverlay active={this.state.isDatacentersLoading}>
                            <select 
                                className="form-control" 
                                name="datacenter_id" 
                                onChange={this.props.handleInputChange}
                                defaultValue={input.datacenter_id}>
                                {this.state.datacenters.map((datacenter) => {
                                    return (<option value={datacenter.id}>{datacenter.datacenter_name}</option>)
                                })}
                            </select>
                        </LoadingOverlay>
                    </div>
                </div>

                <div className="form-group">
                    <label>Hostname</label>
                    <div className="input-group">
                        <input 
                            className="form-control" 
                            type="text" 
                            name="hostname" 
                            placeholder="www.hostname.com"
                            onChange={this.props.handleInputChange}
                            defaultValue={input.hostname} />
                    </div>
                </div>

                <div className="form-group">
                    <label>Customer</label>
                    <div className="input-group">
                        <LoadingOverlay active={this.state.isCustomersLoading}>
                            <select 
                                className="form-control" 
                                name="customer_id" 
                                onChange={this.props.handleInputChange}
                                defaultValue={input.customer_id}>
                                {this.state.customers.map((customer) => {
                                    return (
                                        <option value={customer.id}>
                                            {customer.email} | {customer.full_name}
                                        </option>
                                    )
                                })}
                            </select>
                        </LoadingOverlay>
                    </div>
                </div>

                <div className="form-group">
                    <label>Status</label>
                    <div className="input-group">
                        <LoadingOverlay active={this.state.isStatusesLoading}>
                            <select 
                                className="form-control" 
                                name="status" 
                                onChange={this.props.handleInputChange}
                                defaultValue={input.status}>
                                {this.state.statuses.map((status, index) => {
                                    return (<option value={(index + 1)}>{status}</option>)
                                })}
                            </select>
                        </LoadingOverlay>
                    </div>
                </div>

                <div className="form-group">
                    <label>VAT Size Percentage (%)</label>
                    <div className="input-group">
                        <input 
                            className="form-control" 
                            type="text" 
                            placeholder="10" 
                            name="vat_size_percentage"
                            onChange={this.props.handleInputChange} 
                            defaultValue={input.vat_size_percentage} />
                    </div>
                </div>

                <div className="bg-light">
                    <div className="separator separator-dashed my-8"></div>
                    <h4 className="ml-2">Service Plan</h4>
                    <div className="separator separator-dashed my-8"></div>
                </div>

                <div className="form-group">
                    <label>Service Plan</label>
                    <div className="input-group">
                        <LoadingOverlay active={this.state.isServicePlansLoading}>
                            <select 
                                className="form-control" 
                                name="service_plan_id" 
                                onChange={this.props.handleInputChange}
                                defaultValue={input.service_plan_id}>
                                {this.state.servicePlans.map((plan) => {
                                    return (<option value={plan.id}>{plan.plan_name}</option>)
                                })}
                            </select>
                        </LoadingOverlay>
                    </div>
                </div>

                <div className="form-group">
                    <label>Service Plan Quantity</label>
                    <div className="input-group">
                        <input 
                            className="form-control" 
                            type="number" 
                            name="quantity" 
                            defaultValue={1}
                            onChange={this.props.handleInputChange}
                            defaultValue={input.quantity} />
                    </div>
                </div>

                <div className="form-group">
                    <label>Disk Size (MB)</label>
                    <div className="input-group">
                        <input 
                            className="form-control" 
                            name="disk_size" 
                            type="number"
                            defaultValue={0}
                            onChange={this.props.handleInputChange}
                            defaultValue={input.disk_size}></input>
                    </div>
                </div>

                <div className="form-group">
                    <label>Note (Optional)</label>
                    <div className="input-group">
                        <textarea 
                            className="form-control" 
                            name="note" 
                            rows="5"
                            onChange={this.props.handleInputChange}
                            defaultValue={input.note}></textarea>
                    </div>
                </div>

                <div className="bg-light">
                    <div className="separator separator-dashed my-8"></div>
                    <h4 className="ml-2">Service Addons</h4>
                    <div className="separator separator-dashed my-8"></div>
                </div>
                
                <LoadingOverlay active={this.state.isServiceAddonsLoading}>
                    <table className="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Addon Name</th>
                                <th>Addon Type</th>
                                <th>Price</th>
                                <th>Quantity Unit</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {this.state.serviceAddons.map((addon, index) => {
                                addon.added_quantity = Number.isInteger(addon.added_quantity) ? 
                                    addon.added_quantity : 0

                                return (
                                    <tr>
                                        <td>{addon.addon_name}</td>
                                        <td>{addon.addon_type}</td>
                                        <td>{addon.currency} {addon.addon_fee}</td>
                                        <td>{addon.quantity} {addon.unit}</td>
                                        <td>{addon.description}</td>
                                        <td>
                                            <div className="row">
                                                <div className="col-sm-3 form-group">
                                                    <button 
                                                        onClick={() => this.changeAddonQuantity(addon, --addon.added_quantity)} 
                                                        className="btn btn-sm btn-danger float-right">
                                                        <i className="fas fa-minus ml-1"></i>
                                                    </button>
                                                </div>

                                                <div className="col-sm-6 form-group">
                                                    <input 
                                                        className="form-control" 
                                                        type="number" 
                                                        placeholder="0"
                                                        value={addon.added_quantity ? addon.added_quantity : 0}
                                                        onChange={(event) => this.setAddonQuantity(event, index)} />
                                                </div>

                                                <div className="col-sm-3 form-group">
                                                    <button 
                                                        onClick={() => this.changeAddonQuantity(addon, ++addon.added_quantity)} 
                                                        className="btn btn-sm btn-success float-left">
                                                        <i className="fas fa-plus ml-1"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                )
                            })}
                        </tbody>
                    </table>
                </LoadingOverlay>

                <div className="separator separator-dashed my-8"></div>
            </div>

        )
    }
}

if (document.getElementById('order_form')) {
    ReactDOM.render(<OrderForm />, document.getElementById('order_form'));
}