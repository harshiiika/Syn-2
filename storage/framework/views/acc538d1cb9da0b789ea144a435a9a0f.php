<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>
 
<body style="background-image: url('<?php echo e(asset('images/motion graphic background_12.0de2e3ff0dd28bfa.png')); ?>'); background-size: cover;">
    <div class="signin">
 
        <div class="container">

                <img src="<?php echo e(asset('images/background logo.png')); ?>" alt="logo"
                    class="logo">
                <!-- display validation errors -->
               <?php if($errors->any()): ?>
    <div style="color: red;">
        <ul>

            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<!-- //form to submit login credentials -->
<form method="POST" action="<?php echo e(route('login.submit')); ?>" class="card">
    <?php echo csrf_field(); ?>
    <h3>Login</h3><br>
    <div class="login-form">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" class="username" placeholder="Enter Your Email" required><br>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" class="username" placeholder="Enter Your Password" required><br>
    </div>
    <button class="btn" type="submit">Continue</button>
</form>

</div>
    </div>
    <!-- <script src="<?php echo e(asset('js/login.js')); ?>"></script> -->
</body>

</html>
 <?php /**PATH C:\Users\dhamu\Syn-2\resources\views/auth/login.blade.php ENDPATH**/ ?>