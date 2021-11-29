import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { arrayUpdate, searchInCollection } from '../Helper'

import Pagination from '../Pagination'
import LoadingOverlay from 'react-loading-overlay';

export default class CommandHistory extends Component {
    constructor(props) {
        super(props)

        this.state = {
            allCommandHistories: [],
            commandHistories: [],
            shownCommandHistories: [],
            startIndex: 1,
            isLoading: false,
        }

        this.populateCommandHistories = this.populateCommandHistories.bind(this)

        this.onChangePage = this.onChangePage.bind(this)
        this.toggleLoading = this.toggleLoading.bind(this)
    }

    componentDidMount() {
        this.populateCommandHistories()
    }

    async populateCommandHistories() {
        this.toggleLoading()

        await axios.get('/dashboard/command_histories/populate')
            .then((response) => {
                console.log(response.data.commandHistories)

                this.setState({ 
                    allCommandHistories: response.data.commandHistories,
                    commandHistories: response.data.commandHistories,
                })
            })

        this.toggleLoading()
    }

    async redoCommand(commandHistory) {
        this.toggleLoading()
        await axios.post('/dashboard/command_histories/redo', { id: commandHistory.id })
            .then((response) => {
                let commandHistories = this.state.allCommandHistories
                let newCommandHistories = response.data.commandHistories

                newCommandHistories = newCommandHistories.concat(commandHistories)

                let currentPage = this.paginationRef.state.pager.currentPage
                this.setState({
                    allCommandHistories: newCommandHistories,
                    commandHistories: newCommandHistories,
                })
                this.paginationRef.rePaginate(this.state.allCommandHistories)
                this.paginationRef.setPage(currentPage)
            })
        this.toggleLoading()
    }

    deleteButton(commandHistory) {
        return (
            <a href={'/dashboard/command_histories/confirm_delete?id=' + commandHistory.id}>
                <img src="/assets/media/svg/icons/Home/Trash.svg" />
            </a>
        )
    }

    statusLabel(commandHistory) {
        let status = commandHistory.status

        if (status == 'success') {
            return (
                <span className="label label-success label-inline font-weight-lighter mr-2">
                    Success
                </span>
            )
        } else {
            return (
                <span className="label label-danger label-inline font-weight-lighter mr-2">
                    Failed
                </span>
            )
        }
    }

    search(event) {
        var value = _.clone(event.target.value)
        value = value.toLowerCase()

        this.setState({
            commandHistories: searchInCollection(_.clone(this.state.allCommandHistories), value)
        }, () => {
            this.paginationRef.rePaginate(this.state.commandHistories)
        })
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    onChangePage(shownCommandHistories, startIndex) {
        this.setState({
            shownCommandHistories: shownCommandHistories,
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
                    text="Loading Command Histories...">
                    <table className="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Queue Name</th>
                            <th>Command</th>
                            <th>Executor</th>
                            <th>Status</th>
                            <th>Executed From</th>
                            <th>Executed At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                                {this.state.shownCommandHistories.map((commandHistory, key) => {
                                    return (
                                        <tr key={commandHistory.id}>
                                            <td>{(key + 1)}</td>
                                            <td>{commandHistory.queue_name}</td>
                                            <td><b>{commandHistory.content}</b></td>
                                            <td>{commandHistory.executor_name}</td>
                                            <td>{this.statusLabel(commandHistory)}</td>
                                            <td>{commandHistory.executed_from}</td>
                                            <td>{commandHistory.executed_at}</td>
                                            <td>
                                                <button className="btn btn-sm btn-secondary">
                                                    <i className="fas fa-eye mr-1"></i> Details
                                                </button>
                                                <button onClick={() => this.redoCommand(commandHistory)} className="btn btn-sm btn-primary">
                                                    <i className="fas fa-redo mr-1" /> Redo
                                                </button>
                                            </td>
                                        </tr>
                                    )
                                })}
                        </tbody>
                    </table>
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    <Pagination 
                        items={this.state.commandHistories ? this.state.commandHistories : []} 
                        onChangePage={this.onChangePage}
                        ref={(ref) => this.paginationRef = ref} />
                </div>
            </div>
        )
    }
}

if (document.getElementById('command_history')) {
    ReactDOM.render(<CommandHistory />, document.getElementById('command_history'));
}