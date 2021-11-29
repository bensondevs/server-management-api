import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import parse from 'html-react-parser'

import Pagination from '../Pagination'
import LoadingOverlay from 'react-loading-overlay';
import moment from 'moment';

export default class ActivityLog extends Component {
    constructor(props) {
        super(props)

        this.state = {
            logs: [],

            start: null,
            end: null,
        }

        this.loadActivityLogs = this.loadActivityLogs.bind(this)
    }

    componentDidMount() {
        this.loadActivityLogs()
    }

    async loadActivityLogs() {
        let url = '/dashboard/activity_logs/populate?'

        if (this.state.start)
            url += 'start=' + this.state.start

        if (this.state.end)
            url += 'end=' + this.state.end

        await axios.get(url).then((response) => {
            this.setState({ logs: response.data.logs })
        })
    }

    render() {
        let logs = this.state.logs

        return (
			<div className="col-12 card card-custom card-stretch gutter-b">
				<div className="card-header align-items-center border-0 mt-4">
					<h3 className="card-title align-items-start flex-column">
						<span className="font-weight-bolder text-dark">Activity Logs</span>
						<span className="text-muted mt-3 font-weight-bold font-size-sm">890,344 Actions</span>
					</h3>
					<div className="card-toolbar">
						<div className="dropdown dropdown-inline">
							<a href="#" className="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i className="ki ki-bold-more-hor"></i>
							</a>
							<div className="dropdown-menu dropdown-menu-md dropdown-menu-right">
								<ul className="navi navi-hover ml-3">
									<li className="navi-header font-weight-bold py-4">
										<span className="font-size-lg">Choose Date:</span>
										<i className="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right" title="" data-original-title="Click to learn more..."></i>
									</li>
									<li className="navi-separator mb-3 opacity-70"></li>
									<li className="navi-item">
										<div className="form-group col-11 justify-center">
											<label>Start Datetime</label>
											<input className="form-control" type="datetime-local" name="start" />
										</div>
									</li>
									<li className="navi-item">
										<div className="form-group col-11 justify-center">
											<label>End Datetime</label>
											<input className="form-control" type="datetime-local" name="end" />
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				
				<div className="card-body pt-4">
					{logs.map((log, key) => {
                        return (
                            <div className="timeline timeline-6 mt-3">
                                <div className="timeline-item align-items-start">
                                    <div className="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                                        {moment(log.created_at).format('M/D, YYYY; HH:mm:ss')}
                                    </div>
                                    
                                    <div className="timeline-badge">
                                        <i className="fa fa-genderless text-warning icon-xl"></i>
                                    </div>
                                    
                                    <div className="font-weight-mormal font-size-lg timeline-content text-muted pl-3">
                                        {parse(log.description)}
                                    </div>
                                </div>
                            </div>
                        )
                    })}
				</div>
			</div>
        )
    }
}

if (document.getElementById('activity_log')) {
    ReactDOM.render(<ActivityLog />, document.getElementById('activity_log'));
}