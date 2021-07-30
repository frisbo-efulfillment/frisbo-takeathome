import React, {useEffect, useState} from 'react';
import Table from "./components/Table";
import Dropdown from "./components/Dropdown";
import http from "./utils/http";

type Props = {}
type State = {
    orders: Array<any>
    ordersAreLoading: boolean,
    ordersErrorMessage: string,
    organizations: Array<any>,
    organizationsAreLoading: boolean,
    organizationsErrorMessage: string,
    selectedOrganizationId: string
}
const Takeathome: React.FC<Props> = (props) => {
    const [state, setState] = useState<State>({
        orders: [],
        ordersAreLoading: false,
        ordersErrorMessage: '',
        organizations: [],
        organizationsAreLoading: false,
        organizationsErrorMessage: '',
        selectedOrganizationId: '',
    });

    useEffect(() => {
        setState(state => ({...state, organizationsAreLoading: true}))

        http.get('/api/organizations')
            .then(data => setState(state => ({
                ...state,
                organizations: data,
                organizationsAreLoading: false
            })))
            .catch(error => setState(state => ({
                ...state,
                organizations: [],
                organizationsErrorMessage: error.message,
                ordersAreLoading: false
            })))

    }, []);

    useEffect(() => {
        setState(state => ({...state, ordersAreLoading: true}))

        http.get(`/api/orders?organization_id=${state.selectedOrganizationId}`)
            .then(data => setState(state => ({
                ...state,
                orders: data,
                ordersAreLoading: false
            })))
            .catch(error => {
                setState(state => ({
                    ...state,
                    orders: [],
                    organizationsErrorMessage: error.message,
                    ordersAreLoading: false
                }))
            })

    }, [state.selectedOrganizationId])

    if (state.organizationsErrorMessage) {
        return <h1>Error while loading organizations: {state.organizationsErrorMessage}</h1>
    }

    if (state.ordersErrorMessage) {
        return <h1>Error while loading orders: {state.ordersErrorMessage}</h1>
    }

    return (<div className="container-fluid">
        {state.organizationsAreLoading
            ? <h1>Loading organizations...</h1>
            : <Dropdown organizations={state.organizations}
                        onOrganizationChange={(id) => setState({
                            ...state,
                            selectedOrganizationId: id
                        })}
                        selectedOrganizationId={state.selectedOrganizationId}
            />
        }
        {state.ordersAreLoading
            ? <h1>Loading orders...</h1>
            : <Table orders={state.orders} organizations={state.organizations}/>
        }
    </div>);
}

export default Takeathome;