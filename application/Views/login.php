            <form method="POST" action="<?=$data['BASE_HREF'];?>/login/authenticate">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" title="Username" tabindex="1" value="<?=$data['username'];?>" required autoselect>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" title="Password" tabindex="2"required>
                
                <input type="submit" name="submit" value="Login" tabindex="3">
                <input type="hidden" name="token" value="<?=$data['token'];?>">
            </form>

            <h3><?=$data['error'];?></h3>
