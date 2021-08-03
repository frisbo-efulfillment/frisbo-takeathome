import React from 'react';
import {
    Container,
    FormControl,
    MenuItem,
    Select,
    Table, TableBody, TableCell,
    TableContainer,
    TableHead, TableRow
} from "@material-ui/core";

class Takeathome extends React.Component {

    constructor(props) {
        super(props);
        this.getOrganizations = this.getOrganizations.bind(this);
        this.getOrders = this.getOrders.bind(this);
        this.handleOrganizationChange = this.handleOrganizationChange.bind(this);
        this.state = {
            selectedOrg: 'All',
            organizations: [],
            orders: [],
            error: null,
            isLoaded: false,
        };
    }

    async componentDidMount() {
        try {
            const organizations = await this.getOrganizations()
            const orders = await this.getOrders(this.state.selectedOrg)
            this.setState({organizations, orders, isLoaded: true})
        } catch (error) {
            this.setState({error, isLoaded: true})
        }
    }

    async getOrganizations() {
        const result = await fetch('api/organizations')
        return JSON.parse(await result.text())
    }

    async getOrders(organizationId) {
        let ordersUrl = organizationId === 'All' ? 'api/orders' : 'api/orders/' + organizationId

        const result = await fetch(ordersUrl)
        return JSON.parse(await result.text())
    }

    async handleOrganizationChange(el) {
        this.setState({isLoaded: false})
        const orders = await this.getOrders(el.target.value)
        this.setState({selectedOrg: el.target.value, isLoaded: true, orders})
    }

    render() {
        const {error, isLoaded} = this.state;
        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return <div>Loading...</div>;
        } else {
            return (
                <>
                    <Container maxWidth="sm">
                        <FormControl>
                            <Select
                                labelId="demo-simple-select-helper-label"
                                id="demo-simple-select-helper"
                                value={this.state.selectedOrg}
                                onChange={this.handleOrganizationChange}>
                                <MenuItem value={'All'} key={'All'}>All</MenuItem>
                                {this.state.organizations.map(organization => {
                                    return <MenuItem value={organization.organization_id}
                                                     key={organization.organization_id}>{organization.alias}</MenuItem>
                                })}
                            </Select>
                        </FormControl>
                        <TableContainer>
                            <Table aria-label="simple table">
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Order Id</TableCell>
                                        <TableCell align="right">Organization Name</TableCell>
                                        <TableCell align="right">Ordered Date</TableCell>
                                        <TableCell align="right">Number of products</TableCell>
                                        <TableCell align="right">Fulfillment status</TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>

                                    {this.state.orders.map(order => {
                                        return <TableRow key={order.order_id}>
                                            <TableCell component="th" scope="row">
                                                {order.order_id}
                                            </TableCell>
                                            <TableCell align="right">{order.ordered_date}</TableCell>
                                            <TableCell
                                                align="right">{this.state.organizations.find(o => o.organization_id === order.organization_id).alias}
                                            </TableCell>
                                            <TableCell align="right">{order.products.length}</TableCell>
                                            <TableCell align="right">{order.fulfillment.status}</TableCell>
                                        </TableRow>
                                    })}
                                </TableBody>
                            </Table>
                        </TableContainer>
                    </Container>
                </>
            );
        }
    }
}

export default Takeathome;