import axios from 'axios';
import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import LoadingOverlay from 'react-loading-overlay';

export default class ContainerView extends Component {
    constructor(props) {
        super(props)

        this.state = {
            isLoading: false,

            container: {},
            vpn: {
                status: '',
            },
            samba: {
                nmbd_service: '',
                smbd_service: '',
                start_on_boot: '',
            },
            nfs: {
                status: ''
            },
            nginx: {
                status: ''
            },
            queueName: '',
        }

        this.toggleLoading = this.toggleLoading.bind(this)
        this.getContainer = this.getContainer.bind(this)

        this.startPower = this.startPower.bind(this)
        this.stopPower = this.stopPower.bind(this)
        this.restartPower = this.restartPower.bind(this)
        
        this.checkVpnStatus = this.checkVpnStatus.bind(this)
        this.startVpn = this.startVpn.bind(this)
        this.stopVpn = this.stopVpn.bind(this)
        this.restartVpn = this.restartVpn.bind(this)
        this.enableVpn = this.enableVpn.bind(this)
        this.disableVpn = this.disableVpn.bind(this)

        this.checkSambaStatus = this.checkSambaStatus.bind(this)
        this.startSamba = this.startSamba.bind(this)
        this.stopSamba = this.stopSamba.bind(this)
        this.restartSamba = this.restartSamba.bind(this)

        this.checkNfsStatus = this.checkNfsStatus.bind(this)
        this.startNfs = this.startNfs.bind(this)
        this.stopNfs = this.stopNfs.bind(this)
        this.restartNfs = this.restartNfs.bind(this)

        this.checkNginxStatus = this.checkNginxStatus.bind(this)
        this.startNginx = this.startNginx.bind(this)
        this.stopNginx = this.stopNginx.bind(this)
        this.restartNginx = this.restartNginx.bind(this)
    }

    async componentWillMount() {
        await this.getContainer();
        await this.loadContainer();
    }

    toggleLoading() {
        this.setState({ isLoading: (! this.state.isLoading) })
    }

    async getContainer() {
        let containerViewElement = document.getElementById('container_view')
        let container = containerViewElement.getAttribute('data-container')
        container = JSON.parse(container)
        await this.setState(
            { container: container }, 
            () => console.log(this.state.container)
        )

        return container
    }

    async loadContainer() {
        await this.checkVpnStatus()
        await this.checkSambaStatus()
        await this.checkNfsStatus()
        await this.checkNginxStatus()
    }

    async startPower() {
        this.toggleLoading()

        let request = {
            queue_name: this.state.queueName,
            message: 'power_start',
        }

        await axios.post('/dashboard/rabbitmq/publish', request)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async stopPower() {
        this.toggleLoading()

        let request = {
            queue_name: this.state.queueName,
            message: 'power_stop',
        }

        await axios.post('/dashboard/rabbitmq/publish', request)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async restartPower() {
        this.toggleLoading()

        let request = {
            queue_name: this.state.queueName,
            message: 'power_restart',
        }

        await axios.post('/dashboard/rabbitmq/publish', request)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async checkVpnStatus()
    {
        if (this.state.container) {
            let container = this.state.container
            let url = '/dashboard/containers/' + container.id + '/vpn/check_status';
            await axios.get(url)
                .then((response) => {
                    let status = response.data
                    console.log(status)

                    let vpn = this.state.vpn
                    vpn['status'] = status['vpn_status']
                    this.setState({ vpn: vpn })
                })
        }
    }

    async startVpn() {
        this.toggleLoading()

        let container = this.state.container
        await axios.post('/dashboard/containers/' + container.id + '/vpn/start')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error)
            })
        
        this.toggleLoading()
    }

    async stopVpn() {
        this.toggleLoading()

        let container = this.state.container;
        let url = '/dashboard/containers/' + container.id + '/vpn/stop';
        await axios.post(url)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async restartVpn() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/vpn/restart')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async enableVpn() {
        this.toggleLoading()

        let request = {
            status: 'enable'
        }

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/vpn/toggle_start_on_boot', request)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async disableVpn() {
        this.toggleLoading();

        let request = {
            status: 'disable'
        };
        let container = this.state.container;

        await axios.post('/dashboard/containers/' + container.id + '/vpn/toggle_start_on_boot', request)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })
        
        this.toggleLoading()
    }

    async checkSambaStatus() {
        this.toggleLoading()

        if (this.state.container.id) {
            let container = this.state.container
            await axios.get('/dashboard/containers/' + container.id + '/samba/check_status')
                .then((response) => {
                    let statuses = response.data.status

                    console.log(statuses)

                    let sambaStatuses = this.state.samba
                    sambaStatuses['nmbd_service'] = statuses['nmbd_status']
                    sambaStatuses['smbd_service'] = statuses['smbd_status']
                    this.setState({ samba: sambaStatuses }, () => console.log(this.state.samba))
                })
        }

        this.toggleLoading()
    }

    async startSamba() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/samba/start')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async stopSamba() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/samba/stop')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async restartSamba() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/samba/restart')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async checkNfsStatus() {
        if (this.state.container) {
            this.toggleLoading()
            let container = this.state.container
            await axios.get('/dashboard/containers/' + container.id + '/nfs/check_status')
                .then((response) => {
                    let status = response.data.nfs_status
                    
                    console.log('NFS status')
                    console.log(status)
    
                    let nfs = this.state.nfs
                    nfs['status'] = status
                    this.setState({ nfs: nfs })
                })
            this.toggleLoading()
        }
    }

    async startNfs() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/nfs/start')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async restartNfs() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/nfs/restart')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async stopNfs() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/nfs/stop')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async checkNginxStatus() {
        if (this.state.container) {
            this.toggleLoading()
            let container = this.state.container
            console.log(container)
            await axios.get('/dashboard/containers/' + container.id + '/nginx/check_status')
                .then((response) => {
                    let status = response.data.nginx_status
    
                    let nginx = this.state.nginx
                    nginx['status'] = status
                    this.setState({ nginx: nginx })
                })
            this.toggleLoading()
        }
    }

    async startNginx() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/nginx/start')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async restartNginx() {
        this.toggleLoading()

        let container = this.container;
        await axios.post('/dashboard/containers/' + container.id + '/nginx/restart')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async stopNginx() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/nginx/stop', request)
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    async reloadNginx() {
        this.toggleLoading()

        let container = this.state.container;
        await axios.post('/dashboard/containers/' + container.id + '/nginx/reload')
            .then((response) => {
                console.log(response.data)
            }).catch((error) => {
                console.log(error.response.data)
            })

        this.toggleLoading()
    }

    render() {
        return (
            <div className="card card-custom">
                <div className="card-header card-header-tabs-line nav-tabs-line-3x">
                    <div className="card-toolbar">
                        <ul className="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                            <li className="nav-item mr-3">
                                <a className="nav-link active" data-toggle="tab" href="#power">
                                    <span className="svg-icon svg-icon-2x mr-1">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M7.62302337,5.30262097 C8.08508802,5.000107 8.70490146,5.12944838 9.00741543,5.59151303 C9.3099294,6.05357769 9.18058801,6.67339112 8.71852336,6.97590509 C7.03468892,8.07831239 6,9.95030239 6,12 C6,15.3137085 8.6862915,18 12,18 C15.3137085,18 18,15.3137085 18,12 C18,9.99549229 17.0108275,8.15969002 15.3875704,7.04698597 C14.9320347,6.73472706 14.8158858,6.11230651 15.1281448,5.65677076 C15.4404037,5.20123501 16.0628242,5.08508618 16.51836,5.39734508 C18.6800181,6.87911023 20,9.32886071 20,12 C20,16.418278 16.418278,20 12,20 C7.581722,20 4,16.418278 4,12 C4,9.26852332 5.38056879,6.77075716 7.62302337,5.30262097 Z" fill="#000000" fillRule="nonzero"/>
                                                <rect fill="#000000" opacity="0.3" x="11" y="3" width="2" height="10" rx="1"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span className="nav-text font-size-lg">Power</span>
                                </a>
                            </li>
                            <li className="nav-item mr-3">
                                <a className="nav-link" data-toggle="tab" href="#vpn">
                                    <span className="nav-icon">
                                        <span className="svg-icon">
                                            <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fillRule="nonzero" opacity="0.3"></path>
                                                    <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fillRule="nonzero"></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </span>
                                    <span className="nav-text font-size-lg">VPN</span>
                                </a>
                            </li>
                            <li className="nav-item mr-3">
                                <a className="nav-link" data-toggle="tab" href="#samba">
                                    <span className="nav-icon">
                                    <span className="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M16.5428932,17.4571068 L11,11.9142136 L11,4 C11,3.44771525 11.4477153,3 12,3 C12.5522847,3 13,3.44771525 13,4 L13,11.0857864 L17.9571068,16.0428932 L20.1464466,13.8535534 C20.3417088,13.6582912 20.6582912,13.6582912 20.8535534,13.8535534 C20.9473216,13.9473216 21,14.0744985 21,14.2071068 L21,19.5 C21,19.7761424 20.7761424,20 20.5,20 L15.2071068,20 C14.9309644,20 14.7071068,19.7761424 14.7071068,19.5 C14.7071068,19.3673918 14.7597852,19.2402148 14.8535534,19.1464466 L16.5428932,17.4571068 Z" fill="#000000" fillRule="nonzero"/>
                                                <path d="M7.24478854,17.1447885 L9.2464466,19.1464466 C9.34021479,19.2402148 9.39289321,19.3673918 9.39289321,19.5 C9.39289321,19.7761424 9.16903559,20 8.89289321,20 L3.52893218,20 C3.25278981,20 3.02893218,19.7761424 3.02893218,19.5 L3.02893218,14.136039 C3.02893218,14.0034307 3.0816106,13.8762538 3.17537879,13.7824856 C3.37064094,13.5872234 3.68722343,13.5872234 3.88248557,13.7824856 L5.82567301,15.725673 L8.85405776,13.1631936 L10.1459422,14.6899662 L7.24478854,17.1447885 Z" fill="#000000" fillRule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg>
                                    </span>
                                    </span>
                                    <span className="nav-text font-size-lg">Samba</span>
                                </a>
                            </li>
                            <li className="nav-item mr-3">
                                <a className="nav-link" data-toggle="tab" href="#nfs">
                                    <span className="nav-icon">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M12,13 C10.8954305,13 10,12.1045695 10,11 C10,9.8954305 10.8954305,9 12,9 C13.1045695,9 14,9.8954305 14,11 C14,12.1045695 13.1045695,13 12,13 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M7.00036205,18.4995035 C7.21569918,15.5165724 9.36772908,14 11.9907452,14 C14.6506758,14 16.8360465,15.4332455 16.9988413,18.5 C17.0053266,18.6221713 16.9988413,19 16.5815,19 C14.5228466,19 11.463736,19 7.4041679,19 C7.26484009,19 6.98863236,18.6619875 7.00036205,18.4995035 Z" fill="#000000" opacity="0.3"/>
                                            </g>
                                        </svg>
                                    </span>
                                    <span className="nav-text font-size-lg">NFS</span>
                                </a>
                            </li>
                            <li className="nav-item mr-3">
                                <a className="nav-link" data-toggle="tab" href="#nginx">
                                    <span className="nav-icon">
                                    <span className="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" strokeWidth="1" fill="none" fillRule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M16.5428932,17.4571068 L11,11.9142136 L11,4 C11,3.44771525 11.4477153,3 12,3 C12.5522847,3 13,3.44771525 13,4 L13,11.0857864 L17.9571068,16.0428932 L20.1464466,13.8535534 C20.3417088,13.6582912 20.6582912,13.6582912 20.8535534,13.8535534 C20.9473216,13.9473216 21,14.0744985 21,14.2071068 L21,19.5 C21,19.7761424 20.7761424,20 20.5,20 L15.2071068,20 C14.9309644,20 14.7071068,19.7761424 14.7071068,19.5 C14.7071068,19.3673918 14.7597852,19.2402148 14.8535534,19.1464466 L16.5428932,17.4571068 Z" fill="#000000" fillRule="nonzero"/>
                                                <path d="M7.24478854,17.1447885 L9.2464466,19.1464466 C9.34021479,19.2402148 9.39289321,19.3673918 9.39289321,19.5 C9.39289321,19.7761424 9.16903559,20 8.89289321,20 L3.52893218,20 C3.25278981,20 3.02893218,19.7761424 3.02893218,19.5 L3.02893218,14.136039 C3.02893218,14.0034307 3.0816106,13.8762538 3.17537879,13.7824856 C3.37064094,13.5872234 3.68722343,13.5872234 3.88248557,13.7824856 L5.82567301,15.725673 L8.85405776,13.1631936 L10.1459422,14.6899662 L7.24478854,17.1447885 Z" fill="#000000" fillRule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg>
                                    </span>
                                    </span>
                                    <span className="nav-text font-size-lg">NGINX</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div className="card-body">
                    <div className="tab-content">
                        <div className="tab-pane show px-7 active" id="power" role="tabpanel">
                            <LoadingOverlay
                                active={this.state.isLoading}
                                text="Sending command..."
                                spinner>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Power Start</a>
                                                <span className="text-muted">Turn On</span>
                                            </div>
                                            <button onClick={this.startPower} className="btn btn-success">
                                                <i className="fas fa-play mr-1"></i> Start
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Power Off</a>
                                                <span className="text-muted">Turn Off</span>
                                            </div>
                                            <button onClick={this.stopPower} className="btn btn-danger">
                                                <i className="fas fa-stop mr-1"></i> Stop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Restart</a>
                                                <span className="text-muted">Turn On and Off</span>
                                            </div>
                                            <button onClick={this.restartPower} className="btn btn-warning">
                                                <i className="fas fa-retweet mr-1"></i> Restart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </LoadingOverlay>
                        </div>

                        <div className="tab-pane px-7" id="vpn" role="tabpanel">
                            <LoadingOverlay
                                active={this.state.isLoading}
                                text="Sending command..."
                                spinner>
                                <div className="row mb-10">
                                    <div className="col-2"></div>
                                    <div className="container col-10">
                                        <div className="card-spacer pt-5 bg-white flex-grow-1">
                                            <div className="row row-paddingless">
                                                <div className="col">
                                                    <div className="font-size-sm text-muted font-weight-bold">VPN Status</div>
                                                    <div className="font-size-h4 font-weight-bolder">{this.state.vpn.status}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Start VPN</a>
                                                <span className="text-muted">Start connect to VPN</span>
                                            </div>
                                            <button onClick={this.startVpn} className="btn btn-success">
                                                <i className="fas fa-play mr-1"></i> Start
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Stop VPN</a>
                                                <span className="text-muted">End connection with VPN</span>
                                            </div>
                                            <button onClick={this.stopVpn} className="btn btn-danger">
                                                <i className="fas fa-stop mr-1"></i> Stop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Restart</a>
                                                <span className="text-muted">Turn On and Off</span>
                                            </div>
                                            <button onClick={this.restartVpn} className="btn btn-warning">
                                                <i className="fas fa-retweet mr-1"></i> Restart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Enable</a>
                                                <span className="text-muted">Enable VPN on Boot Start</span>
                                            </div>
                                            <button onClick={this.enableVpn} className="btn btn-primary">
                                                <i className="fas fa-plug mr-1"></i> Enable
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Disable</a>
                                                <span className="text-muted">Disable VPN on Boot Start</span>
                                            </div>
                                            <button onClick={this.disableVpn} className="btn btn-secondary">
                                                <i className="fas fa-handshake-slash mr-1"></i> Disable
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Manage</a>
                                                <span className="text-muted">Manage VPN</span>
                                            </div>
                                            <button onClick={() => { window.location = '/dashboard/containers/' + this.state.container.id + '/vpn/manage' }} className="btn btn-secondary">
                                                <i className="fas fa-tasks mr-1"></i> Manage
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </LoadingOverlay>
                        </div>

                        <div className="tab-pane px-7" id="samba" role="tabpanel">
                            <LoadingOverlay
                                active={this.state.isLoading}
                                text="Sending command..."
                                spinner>
                                <div className="row mb-10">
                                    <div className="col-2"></div>
                                    <div className="container col-10">
                                        <div className="card-spacer pt-5 bg-white flex-grow-1">
                                            <div className="row row-paddingless">
                                                <div className="col">
                                                    <div className="font-size-sm text-muted font-weight-bold">SMBD Service Status</div>
                                                    <div className="font-size-h4 font-weight-bolder">{this.state.samba.smbd_service}</div>
                                                </div>
                                                <div className="col">
                                                    <div className="font-size-sm text-muted font-weight-bold">NMBD Service Status</div>
                                                    <div className="font-size-h4 font-weight-bolder">{this.state.samba.nmbd_service}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Start Samba</a>
                                                <span className="text-muted">Start Samba Server</span>
                                            </div>
                                            <button onClick={this.startSamba} className="btn btn-success">
                                                <i className="fas fa-play mr-1"></i> Start
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Stop Samba</a>
                                                <span className="text-muted">Stop Samba Server</span>
                                            </div>
                                            <button onClick={this.stopSamba} className="btn btn-danger">
                                                <i className="fas fa-stop mr-1"></i> Stop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Restart Samba</a>
                                                <span className="text-muted">Restart Samba Server</span>
                                            </div>
                                            <button onClick={this.restartSamba} className="btn btn-warning">
                                                <i className="fas fa-retweet mr-1"></i> Restart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </LoadingOverlay>
                        </div>

                        <div className="tab-pane px-7" id="nfs" role="tabpanel">
                            <LoadingOverlay
                                active={this.state.isLoading}
                                text="Sending command..."
                                spinner>
                                <div className="row mb-10">
                                    <div className="col-2"></div>
                                    <div className="container col-10">
                                        <div className="card-spacer pt-5 bg-white flex-grow-1">
                                            <div className="row row-paddingless">
                                                <div className="col">
                                                    <div className="font-size-sm text-muted font-weight-bold">NFS Status</div>
                                                    <div className="font-size-h4 font-weight-bolder">{this.state.nfs.status}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Start NFS</a>
                                                <span className="text-muted">Start NFS Server</span>
                                            </div>
                                            <button onClick={this.startNfs} className="btn btn-success">
                                                <i className="fas fa-play mr-1"></i> Start
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Stop NFS</a>
                                                <span className="text-muted">Stop NFS Server</span>
                                            </div>
                                            <button onClick={this.stopNfs} className="btn btn-danger">
                                                <i className="fas fa-stop mr-1"></i> Stop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Restart NFS</a>
                                                <span className="text-muted">Restart NFS Server</span>
                                            </div>
                                            <button onClick={this.restartNfs} className="btn btn-warning">
                                                <i className="fas fa-retweet mr-1"></i> Restart
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Manage</a>
                                                <span className="text-muted">Manage NFS</span>
                                            </div>
                                            <button onClick={() => { window.location = '/dashboard/containers/nfs/manage?id=' + this.state.container.id}} className="btn btn-secondary">
                                                <i className="fas fa-tasks mr-1"></i> Manage
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </LoadingOverlay>
                        </div>

                        <div className="tab-pane px-7" id="nginx" role="tabpanel">
                            <LoadingOverlay
                                active={this.state.isLoading}
                                text="Sending command..."
                                spinner>
                                <div className="row mb-10">
                                    <div className="col-2"></div>
                                    <div className="container col-10">
                                        <div className="card-spacer pt-5 bg-white flex-grow-1">
                                            <div className="row row-paddingless">
                                                <div className="col">
                                                    <div className="font-size-sm text-muted font-weight-bold">NGINX Status</div>
                                                    <div className="font-size-h4 font-weight-bolder">{this.state.nginx.status}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Start NGINX</a>
                                                <span className="text-muted">Start NGINX</span>
                                            </div>
                                            <button onClick={this.startNginx} className="btn btn-success">
                                                <i className="fas fa-play mr-1"></i> Start
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Stop NGINX</a>
                                                <span className="text-muted">Stop NGINX</span>
                                            </div>
                                            <button onClick={this.stopNfs} className="btn btn-danger">
                                                <i className="fas fa-stop mr-1"></i> Stop
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-1"></div>
                                    <div className="container col-11">
                                        <div className="d-flex align-items-center mb-10">
                                            <div className="d-flex flex-column flex-grow-1 font-weight-bold">
                                                <a href="#" className="text-dark text-hover-primary mb-1 font-size-lg">Restart NGINX</a>
                                                <span className="text-muted">Restart NGINX</span>
                                            </div>
                                            <button onClick={this.restartNginx} className="btn btn-warning">
                                                <i className="fas fa-retweet mr-1"></i> Restart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </LoadingOverlay>
                        </div>
                    </div>
                </div>
            </div>
        )
    }
}

if (document.getElementById('container_view')) {
    ReactDOM.render(<ContainerView />, document.getElementById('container_view'));
}