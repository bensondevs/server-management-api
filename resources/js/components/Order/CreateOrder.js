import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import LoadingOverlay from 'react-loading-overlay';

import OrderForm from './Form/OrderForm';

export default class CreateOrder extends Component {
    constructor(props) {
        super(props)

        this.state = {
            isLoading: false,

            input: {},
        }

        this.setInputValue = this.setInputValue.bind(this)
        this.handleInputChange = this.handleInputChange.bind(this)
        this.handleAddonChange = this.handleAddonChange.bind(this)

        this.submitOrder = this.submitOrder.bind(this)
    }

    componentDidMount() {
        //
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

    toggleLoading() {
        this.setState({ 
            isLoading: (! this.state.isLoading) 
        })
    }

    async submitOrder() {
        let input = this.state.input
        console.log(input)
        await axios.post('/dashboard/orders/store', input)
            .then((response) => {
                let order = response.data.order
                window.location = '/dashboard/orders/' + order.id + '/view';
            }).catch((error) => {
                console.log(error.response.data)
                alert(error.response.data)
            })
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

                    <button onClick={this.submitOrder} type="submit" className="btn btn-success">
                        <i className="fas fa-plus mr-1"></i> Store
                    </button>
                </div>
            </LoadingOverlay>
        )
    }
}

if (document.getElementById('create_order')) {
    ReactDOM.render(<CreateOrder />, document.getElementById('create_order'));
}