import './App.css';
import {Account} from './components/Account';
import Login from './components/Login';
import SignUp from './components/SignUp';
import Status from './components/Status';

function App() {
  return (
    <div className="App">
      <Account>
        <Status/>
        <SignUp/>
        <Login/>
      </Account>
    </div>
  );
}

export default App;
