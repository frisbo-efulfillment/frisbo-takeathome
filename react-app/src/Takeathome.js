import React from 'react';
import './Takehome.css';
class Takeathome extends React.Component {
    constructor(props) {
        super(props);
        this.backend_url = "/api";
        this.state = {
            error: null,
            isLoaded: false,
            selectedOrg: 'ALL',
            result: [],
            orders:[]
        };
    }


    fetchOrders = () => {
        const url = this.state.selectedOrg === 'ALL'
            ? `${this.backend_url}/orders`
            : `${this.backend_url}/orders?organization_id=${this.state.selectedOrg}`;

        fetch(url)
            .then(response => response.json())
            .then(
                (result) => {
                    console.log('Orders ', result);
                    const ordersData = Array.isArray(result) ? result : (result.data || []);
                    this.setState({
                        isLoaded: true,
                        orders: ordersData
                    });
                },
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }

    handleOrgChange = (event) => {
        console.log(event.target.value);
        this.setState({ selectedOrg: event.target.value }, () => {
            this.fetchOrders();
        })
    }

    componentDidMount() {
        fetch(`${this.backend_url}/organizations`)
            .then(response => response.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        organizations: result.data
                    });
                    this.fetchOrders();
                },
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error
                    });
                }
            )
    }

    render() {
        const { error, isLoaded, result,orders,organizations } = this.state;
        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return <div>Loading...</div>;
        } else {
            return (
                <div className="takeathome-container">
                    <h1>Frisbo Takeathome Project</h1>

                    <select
                        value={this.state.selectedOrg}
                        onChange={this.handleOrgChange}
                        className="org-select"
                    >
                        <option value="ALL">ALL</option>
                        {organizations.map(org => (
                            <option key={org.organization_id} value={org.organization_id}>
                                {org.alias}
                            </option>
                        ))}
                    </select>

                    <table className="orders-table">
                        <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Organization Name</th>
                            <th>Ordered Date</th>
                            <th>Number of products</th>
                            <th>Fulfillment status</th>
                        </tr>
                        </thead>
                        <tbody>
                        {orders.map((order, index) => (
                            <tr key={order.order_id || `order-${index}`}>
                                <td>{order.order_id}</td>
                                <td>{order.organization_name}</td>
                                <td>{order.ordered_date}</td>
                                <td>{order.number_of_products}</td>
                                <td>{order.fulfillment_status}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>
            );
        }
    }
}

export default Takeathome;