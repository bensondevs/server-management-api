import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { searchInCollection } from '../Helper'

import Pagination from '../Pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class LoadCommand extends Component {
    constructor(props) {
        super(props)

        this.state = {
            selectedDestination: '',
            commands: [],
            destinations: [],
        }

        this.addCommand = this.addCommand.bind(this)
        this.removeCommand = this.removeCommand.bind(this)
        this.moveUpCommand = this.moveUpCommand.bind(this)
        this.moveDownCommand = this.moveDownCommand.bind(this)

        this.onCommandChange = this.onCommandChange.bind(this)
        this.onDestinationChange = this.onDestinationChange.bind(this)

        this.buttonUpCommand = this.buttonUpCommand.bind(this)
        this.buttonDownCommand = this.buttonDownCommand.bind(this)

        this.sendCommands = this.sendCommands.bind(this)
        this.populateDestinations = this.populateDestinations.bind(this)
    }

    componentDidMount() {
        this.populateDestinations()
        this.addCommand()
    }

    addCommand() {
        let commands = this.state.commands
        commands.push('')
        this.setState({ commands: commands })
    }

    removeCommand(index) {
        let commands = this.state.commands
        commands.splice(index, 1)
        this.setState({
            commands: commands
        })
    }

    buttonRemoveCommand(key) {
        return (this.state.commands.length > 1) ? (
            <span onClick={() => this.removeCommand(key)} className="input-group-text btn btn-danger">
                <i className="fas fa-times"></i>
            </span>
        ) : (
            <span className="input-group-text btn btn-danger disabled">
                <i className="fas fa-times"></i>
            </span>
        )
    }

    moveUpCommand(index) {
        let commands = this.state.commands
        let movedCommand = commands[index]
        let targetedCommand = commands[(index - 1)]
        commands[(index - 1)] = movedCommand
        commands[index] = targetedCommand

        this.setState({ commands: commands })
    }

    buttonUpCommand(key) {
        return (key > 0) ? (
            <span onClick={() => this.moveUpCommand(key)} className="input-group-text btn btn-primary">
                <i className="fas fa-arrow-up"></i>
            </span>
        ) : (
            <span className="input-group-text btn btn-primary disabled">
                <i className="fas fa-arrow-up"></i>
            </span>
        )
    }

    moveDownCommand(index) {
        let commands = this.state.commands
        let movedCommand = commands[index]
        let targetedCommand = commands[(index + 1)]
        commands[(index + 1)] = movedCommand
        commands[index] = targetedCommand

        this.setState({ commands: commands })
    }

    buttonDownCommand(key) {
        console.log(this.state.commands.length)
        return (key < (this.state.commands.length - 1) && this.state.commands.length > 1) ?
            (
                <span onClick={() => this.moveDownCommand(key)} className="input-group-text btn btn-primary">
                    <i className="fas fa-arrow-down"></i>
                </span>
            ) : (
                <span className="input-group-text btn btn-primary disabled">
                    <i className="fas fa-arrow-down"></i>
                </span>
            )
    }

    onCommandChange(event, key) {
        let target = event.target
        let value = target.value

        let commands = this.state.commands
        commands[key] = value

        this.setState({ commands: commands })
    }

    onDestinationChange(event, key) {
        let target = event.target
        let value = target.value

        this.setState({ selectedDestination: value })
    }

    async populateDestinations() {
        await axios.get('/dashboard/servers/populate')
            .then((response) => {
                let servers = response.data.servers
                this.setState({ 
                    destinations: servers,
                    selectedDestination: servers[0].server_name, 
                })
            })
    }

    async sendCommands() {
        let queue = this.state.selectedDestination
        let commands = JSON.stringify(this.state.commands)

        let input = {
            queue: queue,
            commands: commands
        }

        await axios.post('/dashboard/command_histories/execute_commands', input)
            .then((response) => {
                console.log(response.data)
                window.location = '/dashboard/command_histories/'
            }).catch((error) => {
                console.log(error.response)
            })
    }

    render() {
        return (
            <div>
                <h5>Destination</h5>
                <div className="form-group">
                    <label>Queue Name</label>
                    <div className="input-group">
                        <select 
                            onChange={this.onDestinationChange}
                            defaultValue={this.state.selectedDestination} 
                            className="form-control">
                            {this.state.destinations.map((destination) => {
                                return (
                                    <option value={destination.server_name}>
                                        {destination.server_name}
                                    </option>
                                )
                            })}
                        </select>
                    </div>
                </div>

                <h5>Commands</h5>
                {this.state.commands.map((command, key) => {
                    return (
                        <div className="form-group">
                            <label>Command #{(key + 1)}</label>
                            <div className="input-group">
                                <input 
                                    type="text" 
                                    className="form-control" 
                                    aria-label="Text input with radio"
                                    onChange={(event) => this.onCommandChange(event, key)}
                                    value={this.state.commands[key]} />
                                <div className="input-group-append">
                                    {this.buttonUpCommand(key)}
                                    {this.buttonDownCommand(key)}
                                    {this.buttonRemoveCommand(key)}
                                </div>
                            </div>
                        </div>
                    )
                })}

                <button onClick={this.addCommand} className="btn btn-primary float-right">
                    <i className="fas fa-plus mr-1"></i> Add Command
                </button>

                <br /><br />

                <hr></hr>

                <button onClick={() => this.sendCommands()} className="btn btn-success mr-1">
                    <i className="fas fa-paper-plane mr-1"></i> Execute Commands
                </button>
                <a href="/dashboard/command_histories" className="btn btn-secondary">
                    <i className="fas fa-backward mr-1"></i> Back
                </a>
            </div>
        )
    }
}

if (document.getElementById('load_command')) {
    ReactDOM.render(<LoadCommand />, document.getElementById('load_command'));
}