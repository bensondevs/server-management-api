/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./components/Server/Server');

require('./components/Datacenter/Datacenter');
require('./components/Datacenter/Subnet');

require('./components/Subnet/Subnet');
require('./components/Subnet/IP/SubnetIP');

require('./components/ServicePlan/ServicePlan');
require('./components/ServicePlan/ServicePlanOrder');

require('./components/ServiceAddon/ServiceAddon');

require('./components/CommandHistory/CommandHistory');
require('./components/CommandHistory/LoadCommand');

require('./components/Container/Container');
require('./components/Container/ContainerView');
require('./components/Container/ContainerCommandExecutions');

require('./components/Order/Order');
require('./components/Order/OrderView');
require('./components/Order/CreateOrder');
require('./components/Order/EditOrder');

require('./components/Payment/Payment');
require('./components/Payment/PaymentView');

require('./components/Newsletter/Newsletter');
require('./components/Settings/ActivityLog');

require('./components/User/User');
require('./components/Administrator/Administrator');
require('./components/Administrator/PromoteUser');