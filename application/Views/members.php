            <h1>Welcome <?=$data['user']->username;?></h1>

            <p>
                You are logged in.
            </p>

            <ul>
                <li>
                    Username: <strong><?=$data['user']->username;?></strong>
                </li>
                
                <li>
                    Email: <strong><?=$data['user']->email;?></strong>
                </li>
                
                <li>
                    Timezone: <strong><?=$data['user']->timezone;?></strong>
                </li>
            </ul>
