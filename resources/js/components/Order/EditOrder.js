import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import LoadingOverlay from 'react-loading-overlay';
import { urlParams } from '../Helper';

import OrderForm from './Form/OrderForm';

export default class EditOrder extends Component {
    constructor(props) {
        super(props)

        this.state = {
            isLoading: false,

            order: {},
            input: {},
        }

        this.setInputValue = this.setInputValue.bind(this)
        this.handleInputChange = this.handleInputChange.bind(this)
        this.handleAddonChange = this.handleAddonChange.bind(this)

        this.loadOrder = this.loadOrder.bind(this)
        this.updateOrder = this.updateOrder.bind(this)
    }

    componentDidMount() {
        // Load order
        this.loadOrder()
    }

    handleInputChange(event) {
        const target = event.target
        const name = target.name
        const value = target.value

        let input = this.state.input
        input[name] = value
        this.setState({ input: input })
    }

    handleAddonChange(newAddedAddons = []) {
        let addedAddonsJson = (newAddedAddons !== []) ? 
            JSON.stringify(newAddedAddons) : null
        let input = this.state.input
        input['addons_list'] = addedAddonsJson
        this.setState({ input: input })
    }

    setInputValue(inputKey, value) {
        let input = this.state.input
        input[inputKey] = value
        this.setState({ input: input })
    }

    setInitialInput(order) {
        let input = this.state.input

        // Order Informations
        input.datacenter_id = order.meta_container.datacenter_id
        input.hostname = order.meta_container.hostname
        input.customer_id = order.customer_id
        input.status = order.status
        input.vat_size_percentage = order.vat_size_percentage
        
        // Service Plan Information
        input.service_plan_id = order.plan.service_plan_id
        input.quantity = order.plan.quantity
        input.note = order.plan.note

        // Service Addon
        

        this.setState({ input: input }, console.log(input))
    }

    toggleLoading() {
        this.setState({ 
            isLoading: (! this.state.isLoading) 
        })
    }

    async loadOrder() {
        let params = urlParams()
        await axios.get('/dashboard/orders/load?id=' + params.get('id'))
            .then((response) => {
                this.setState({ order: response.data.order })
                console.log(response.data)
                this.setInitialInput(response.data.order)
            }).catch((error) => console.log(error.response.data))
    }

    async updateOrder() {
        this.toggleLoading()

        let input = this.state.input
        await axios.post('/dashboard/orders/update', input)
            .then((response) => {
                let order = response.data.order
                console.log(response.data)
                window.location = '/dashboard/orders/view?id=' + order.id
            }).catch((error) => {
                console.log(error.response.data)
                alert(error.response.data)
            })
        
        this.toggleLoading()
    }

    render() {
        return (
            <LoadingOverlay
                active={this.state.isLoading}
                spinner
                text="Loading Command Histories...">
                <OrderForm
                    input={this.state.input} 
                    setInputValue={this.setInputValue}
                    handleInputChange={this.handleInputChange}
                    handleAddonChange={this.handleAddonChange}></OrderForm>

                <div className="float-right">
                    <a className="btn btn-secondary mr-3" href="/dashboard/orders/">
                        Cancel
                    </a>

                    <button onClick={this.updateOrder} type="submit" className="btn btn-success">
                        <i className="fas fa-plus mr-1"></i> Store
                    </button>
                </div>
            </LoadingOverlay>
        )
    }
}

if (document.getElementById('edit_order')) {
    ReactDOM.render(<EditOrder />, document.getElementById('edit_order'));
}