            <form method="POST" action="<?=$data['BASE_HREF'];?>/register/user">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" title="Username" tabindex="1" required autofocus>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" title="Password" tabindex="2" required>
                
                <select name="timezone" tabindex="3" required>
                    <option value="-1" selected disabled>Select Timezone</option>
                    <?php foreach ($data['timezones'] as $tz) { ?>

                        <option value="<?=$tz;?>"<?php if ($tz === $data['timezone']) echo " selected"; ?>><?=$tz;?></option>
                    <?php } ?>
                    
                </select>
                
                <input type="submit" name="submit" value="Register" tabindex="4">
                <input type="hidden" name="token" value="<?=$data['token'];?>">
            </form>

            <h3><?=$data['error'];?></h3>
