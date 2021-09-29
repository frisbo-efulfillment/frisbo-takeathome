import './App.css';
import Takeathome from './Takeathome'
import {loginUser} from './actions/auth'

function App() {

    const token = sessionStorage.getItem('token');
    if (!token) {
        loginUser();
    }

    return (
        <div className="App">
            <Takeathome></Takeathome>
        </div>
    );
}

export default App;
