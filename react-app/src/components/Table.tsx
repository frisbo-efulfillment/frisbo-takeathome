import React from "react";

type Props = {
    orders: Array<any>,
    organizations: Array<any>
}
const Table : React.FC<Props> = (props) => {
    const organizationsMap : Map<number, string> = new Map(props.organizations.map(org => [org.organization_id, org.alias]))
    return (
        <table className="table table-striped table-bordered">
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
                {props.orders.map((order) => (<tr key={order.order_id}>
                    <td>{order.order_id}</td>
                    <td>{organizationsMap.get(order.organization_id)}</td>
                    <td>{order.ordered_date}</td>
                    <td>{order.products.length}</td>
                    <td>{order.fulfillment.status}</td>
                </tr>))}
            </tbody>
        </table>
    );
}

export default Table;