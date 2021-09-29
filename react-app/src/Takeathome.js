import React from 'react';
import http from "./helpers/http";
import {FormGroup, Label, Input, Row, Col} from 'reactstrap';
import DataTable from "react-data-table-component";
import "styled-components";

class Takeathome extends React.Component {

    constructor(props) {
        super(props);
        this.backend_url = "api/organizations";
        this.state = {
            error: null,
            isLoaded: false,
            result: "",
            orders: {}
        };
    }

    async componentDidMount() {

        const result = await http.route(this.backend_url).get({});
        if (!result.isError) {
            this.setState({
                isLoaded: true,
                result: result.data
            });
        }else{
            this.setState({
                isLoaded: true,
                error: result
            });
        }
    };

    _changeOrganization = async (e) => {
        e.preventDefault();
        const result = await http.route(`${this.backend_url}/${e.target.value}/orders`).get({});
        if (!result.isError) {

            const columns = [
                {
                    id: 1,
                    name: "Status",
                    selector: (row) => row.status
                },
                {
                    id: 2,
                    name: "Created At",
                    selector: (row) => row.created_at
                },
                {
                    id: 3,
                    name: "Updated At",
                    selector: (row) => row.created_at
                },
                {
                    id: 4,
                    name: "Fullfilment",
                    selector: (row) => row.fullfilment
                }
            ];

            const data = result.data.data.map((value, key) => (
                {
                    id: key,
                    status: value.status,
                    created_at: value.created_at,
                    updated_at: value.updated_at,
                    fullfilment: value.fulfillment.status,
                }
            ));

            this.setState({
                isLoaded: true,
                orders: {
                    columns: columns,
                    data: data
                }
            });
        }
    };


    render() {
        const {error, isLoaded, result, orders} = this.state;
        console.log(orders);
        if (error) {
            return <div>Error: {error}</div>;
        } else if (!isLoaded) {
            return <div>Loading...</div>;
        } else {
            return (
                <Row>
                    <Col xs={12}>
                        <FormGroup>
                            <Label for="exampleSelect">Select:</Label>
                            <Input onChange={(e) => this._changeOrganization(e)} type="select" name="select"
                                   id="exampleSelect">
                                <option> Select Organization</option>
                                {result && result.data.map((value, key) => (
                                    <option key={key} value={value.organization_id}>{value.name}</option>
                                ))}
                            </Input>
                        </FormGroup>
                    </Col>
                    <Col xs={12}>
                        <DataTable
                            title="Orders"
                            columns={orders.columns}
                            data={orders.data}
                        />
                    </Col>
                </Row>


            );
        }
    }
}

export default Takeathome;