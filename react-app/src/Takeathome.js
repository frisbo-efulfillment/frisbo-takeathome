import React from 'react';

class Takeathome extends React.Component {
    

    constructor(props) {
        super(props);
        this.backend_url = "/api";
        this.state = {
            error: null,
            isLoaded: false,
            result: ""
        };
    }

    componentDidMount() {
        fetch(this.backend_url)
            .then(response => response.text())
            .then(
                (result) => {
                    this.setState({
                        isLoaded: true,
                        result: result
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

    render() {
        const { error, isLoaded, result } = this.state;
        if (error) {
            return <div>Error: {error.message}</div>;
        } else if (!isLoaded) {
            return <div>Loading...</div>;
        } else {
            return (
                <h1>{result}</h1>
            );
        }
    }
}

export default Takeathome;