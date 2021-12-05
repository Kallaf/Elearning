import React, { useState } from "react";
import UserPool from "../UserPool";
import { CognitoUserAttribute } from "amazon-cognito-identity-js";

const SignUp = () => {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const onSubmit = (event) => {
        event.preventDefault();
        var attributeList = [
            new CognitoUserAttribute({
                Name: 'email',
                Value: email,
            })
        ];
        UserPool.signUp(email, password, attributeList, null, (err, data) => {
            if (err) {
                console.log("err:", err);
            }
            console.log(data);
        })
    }

    return (
        <div>
            <form onSubmit={onSubmit}>
                <label htmlFor="email">Email</label>
                <input value={email} onChange={(event) => setEmail(event.target.value)}></input>
                <label htmlFor="password">Password</label>
                <input value={password} onChange={(event) => setPassword(event.target.value)}></input>
                <button type="submit">Sign Up</button>
            </form>
        </div>
    );
}

export default SignUp;
