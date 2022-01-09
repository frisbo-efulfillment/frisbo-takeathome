import React from "react";
import Box from "@mui/material/Box";
import InputLabel from "@mui/material/InputLabel";
import MenuItem from "@mui/material/MenuItem";
import FormControl from "@mui/material/FormControl";
import Select from "@mui/material/Select";
import Divider from "@mui/material/Divider";
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import { styled } from '@mui/material/styles';
import Typography from '@mui/material/Typography';

import "./Takeathome.css";
import Loader from "./Loader.js";

const Div = styled('div')(({ theme }) => ({
    ...theme.typography.button,
    backgroundColor: theme.palette.background.paper,
    padding: theme.spacing(1),
}));

class Takeathome extends React.Component {
    constructor(props) {
        super(props);
        this.backend_url = "/api/organizations";
        this.state = {
            error: null,
            isLoaded: false,
            result: "",
            organizationAlias: "",
            orders: [],
            ordersLoading: true,
        };
    }

    componentDidMount() {
        fetch(this.backend_url)
            .then((response) => response.json())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        result: result,
                    });
                },
                (error) => {
                    this.setState({
                        isLoaded: true,
                        error,
                    });
                }
            );
    }

    handleOrders(e) {
        this.setState({
            ordersLoading: true
        })

        // get organization object from select event
        let organization = e.target.value

        // create var param that will be used for fetch URL
        let param;

        // assign value to param based on select event value
        // if ALL assign 0 => get all orders
        // else assign the id of existing organizations
        organization == 'ALL' ? param = 0 : param = organization.organization_id

        // fetch orders with param
        fetch(`/api/orders/${param}`)
            .then((response) => response.json())
            .then(
                (result) => {
                    this.setState({
                        orders: result,

                        // used before selecting any organization for displaying orders for said org.
                        ordersLoading: false
                    });
                },
                (error) => {
                    this.setState({
                        error,
                    });
                }
            );

        // assign organization alias to be showed in the table
        this.setState({
            organizationAlias: organization.alias
        })
    }

    render() {
        const { error, isLoaded, result, orders, organizationAlias, ordersLoading } = this.state;
        // filter orders received from fetch request and sort them by ordered_date in a descending order
        const filterOrdersByOrderedDateDesc = orders?.data?.sort((a, b) => new Date(b.ordered_date) - new Date(a.ordered_date));

        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return <div>Loading...</div>;
        } else {
            return (
                <>
                    <div className="header">
                        <Typography variant="h3" component="div" gutterBottom>
                            Frisbo Homework
                        </Typography>

                        <Div><span className="profession">DEV: </span>Liviu Andrei</Div>
                    </div>

                    <div className="container">
                        <Box className="organisations-select">
                            <FormControl fullWidth>
                                <InputLabel id="organisations">Organisations</InputLabel>
                                <Select
                                    labelId="organisations"
                                    id="organisations-select"
                                    label="Organisations"
                                    defaultValue=""
                                    onChange={(e) => this.handleOrders(e)}
                                >
                                    <MenuItem value={0}>All</MenuItem>
                                    {result?.map((organization) => (
                                        <MenuItem
                                            key={organization.organization_id}
                                            value={organization}
                                        >
                                            {organization.alias}
                                        </MenuItem>
                                    ))}
                                </Select>
                            </FormControl>
                        </Box>

                        <Divider />

                        {ordersLoading ? (
                            <>
                                <div className="ordersLoading">Either data is loading or you need to choose an organization first...</div>
                                <Loader />
                            </>
                        ) : (
                            <TableContainer component={Paper} className="table">
                                <Table sx={{ minWidth: 650 }} aria-label="simple table">
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>Order Id</TableCell>
                                            <TableCell align="right">Organization Name - (id)</TableCell>
                                            <TableCell align="right">Ordered Date</TableCell>
                                            <TableCell align="right">Number of products</TableCell>
                                            <TableCell align="right">Fulfillment status</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {filterOrdersByOrderedDateDesc?.map((order) => (
                                            <TableRow
                                                key={order.order_id}
                                                sx={{ "&:last-child td, &:last-child th": { border: 0 } }}
                                            >
                                                <TableCell component="th" scope="row">
                                                    {order.order_id}
                                                </TableCell>
                                                <TableCell align="right">{`${organizationAlias ? organizationAlias : order.alias} - (${order.organization_id})`}</TableCell>
                                                <TableCell align="right">{order.ordered_date}</TableCell>
                                                <TableCell align="right">{order.products.length}</TableCell>
                                                <TableCell align="right">
                                                    <span
                                                        className={
                                                            order.fulfillment.status == 'Delivered' ? 'success' :
                                                                order.fulfillment.status == 'Ready for picking' ? 'pre-success' :
                                                                    order.fulfillment.status == 'Canceled' ? 'canceled' :
                                                                        order.fulfillment.status == 'Pending fulfillment' ? 'other' :
                                                                            order.fulfillment.status == 'Waiting for courier' ? 'other' : ''
                                                        }
                                                    >
                                                        <b>{order.fulfillment.status}</b>
                                                    </span>
                                                </TableCell>
                                            </TableRow>
                                        ))}
                                    </TableBody>
                                </Table>
                            </TableContainer>
                        )}
                    </div>
                </>
            );
        }
    }
}

export default Takeathome;
