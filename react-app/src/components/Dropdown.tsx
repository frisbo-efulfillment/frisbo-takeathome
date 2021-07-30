import React from "react";

type Props = {
    organizations: Array<any>,
    onOrganizationChange: ( organizationId: string ) => void
    selectedOrganizationId: string
}
const Dropdown : React.FC<Props> = (props) => {

    const keyValuePairs = props.organizations.map(org => [org.organization_id, org.alias])

    return (
        <form className="form">
            <select className="form-select" value={props.selectedOrganizationId} onChange={(e) => { props.onOrganizationChange(e?.target?.value)}}>
                <option value={''}>ALL</option>
                {keyValuePairs.map(([value, label]) => <option key={value} value={value}>{label}</option>)}
            </select>
        </form>
    );
}

export default Dropdown;