import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import moment from 'moment';

import LoadingOverlay from 'react-loading-overlay';
import { PerPageOptions } from '../PerPageOptions';

export default class ContainerCommandExecutions extends Component {
    constructor(props) {
        super(props);

        this.state = {
            executions: [],
            statusBadges: [],

            is_loading: false,
        };
    }

    componentDidMount() {
        this.loadStatusBadges();
        this.loadExecutions();
    }

    async loadExecutions(reload = false) {
        if (document.getElementById('container_command_executions').getAttribute('data-executions') && reload == false) {
            let executionsJson = document.getElementById('container_command_executions').getAttribute('data-executions');
            let executions = JSON.parse(executionsJson);
            return this.setState({ executions: executions });
        }

        let executions = await axios.get(window.location.href);
        executions = executions.data;
        this.setState({ executions: executions });
    }

    async loadStatusBadges() {
        let badges = await axios.get('/meta/job_tracker/status_badges');
        await this.setState({ statusBadges: badges.data });
    }

    statusBadges(status = 1) {
        if (! this.state.statusBadges) {
            this.loadStatusBadges();
        }

        let badge = this.state.statusBadges[status.toString()];
        if (! badge) {
            return (<span className={''}>Loading...</span>);
        }
        let content = badge.content;
        let className = badge.class;

        return (<span className={className}>{content}</span>);
    }

    renderExecutionsTable() {
        let executions = this.state.executions;

        return (
            <table className="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Executed At</th>
                        <th>Job Class</th>
                        <th>Status</th>
                        <th>Response</th>
                    </tr>
                </thead>

                <tbody>
                    {executions.map((execution) => {
                        return (
                            <tr key={execution.id}>
                                <td>{moment(execution.created_at).format('MMMM Do YYYY, h:mm:ss a')}</td>
                                <td>{execution.job_class}</td>
                                <td>{this.statusBadges(execution.status)}</td>
                                <td className="overflow-auto">{execution.return_response}</td>
                            </tr>
                        )
                    })}
                </tbody>
            </table>
        );
    }

    render() {
        return (
            <div>
                <div className="mt-2 mb-5 mt-lg-5 mb-lg-10">
                    <div className="row align-items-center">
                        <div className="col-lg-9 col-xl-8">
                            <div className="row align-items-center">
                                <div className="col-md-4 my-2 my-md-0">
                                    {/* <SearchInput handleSearch={this.handleSearch}></SearchInput> */}
                                </div>

                                <div className="col-md-2 my-2 my-md-0">
                                    {/* {this.renderPerPageOptions()} */}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <LoadingOverlay
                    active={this.state.isLoading}
                    spinner
                    text="Loading Containers...">
                    {this.renderExecutionsTable()}
                </LoadingOverlay>

                <div className="d-flex justify-content-center">
                    {/* {this.renderPagination()} */}
                </div>
            </div>
        )
    }
}

if (document.getElementById('container_command_executions')) {
    ReactDOM.render(<ContainerCommandExecutions />, document.getElementById('container_command_executions'));
}