import axios from 'axios';
import React, { Component } from 'react';

export default class ManageContainerVpn extends Component {
    constructor(props) {
        super(props)

        this.state = {
            isLoading: false,

            container: {},
            pidNumbers: [],
        }
    }

    async populatePidNumbers() {
        await axios.get('/dashboard/containers/vpn/check_pid_numbers')
            .then((response) => {
                let pidNumbers = response.data.pid_numbers
                this.setState({ pidNumbers: pidNumbers })
            })
    }

    async populateUsers() {
        
    }

    render() {
        return (
            <div className="card card-custom">
                <table className="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>PID Number</th>
                    </tr>
                    </thead>
                    <tbody>
                            {data?.map((datacenter, key) => {
                                return (
                                    <tr key={datacenter.id}>
                                        <td>{(key + 1)}</td>
                                        <td>{datacenter.region_name}</td>
                                        <td>{datacenter.datacenter_name}</td>
                                        <td>
                                            {datacenter.client_datacenter_name}
                                        </td>
                                        <td>{datacenter.location}</td>
                                        <td>
                                            <span className={'label label-' + (datacenter.status == 'active' ? 'success' : 'danger') + ' label-inline font-weight-lighter mr-2'}>
                                                {datacenter.status == 'active' ? 'Active' : 'Inactive'}
                                            </span>
                                        </td>
                                        <td>
                                            {this.toggleButton(datacenter)}
                                            
                                            {this.viewButton(datacenter)}
                                            {this.editButton(datacenter)}
                                            {this.deleteButton(datacenter)}
                                        </td>
                                    </tr>
                                )
                            })}
                    </tbody>
                </table>
            </div>
        )
    }
}