import React, { createContext } from "react";
import Pool from "../UserPool";
import { CognitoUser, AuthenticationDetails } from "amazon-cognito-identity-js";

const AccountContext = createContext();

const Account = (props) => {
    const getSession = async () => {
        return await new Promise((resolve, reject) => {
            const user = Pool.getCurrentUser();
            if (user) {
                user.getSession((err, session) => {
                    if (err) {
                        reject();
                    } else {
                        resolve(session);
                    }
                })
            } else {
                reject();
            }
        })
    }
    const authenticate = async (Username, Password) => {
        return await new Promise((resolve, reject) => {
            const user = new CognitoUser({ Username, Pool });
            const authDetails = new AuthenticationDetails({ Username, Password });

            user.authenticateUser(authDetails, {
                onSuccess: (data) => {
                    resolve(data);
                },
                newPasswordRequired: (data) => {
                    resolve(data);
                },
                onFailure: (err) => {
                    reject(err);
                }
            });
        })
    }
    const logout = async () => {
        const user = Pool.getCurrentUser();
        if (user) {
            user.signOut();
        }
    }
    return (
        <AccountContext.Provider value={{ authenticate, getSession, logout }}>
            {props.children}
        </AccountContext.Provider>
    );
}

export { Account, AccountContext };
