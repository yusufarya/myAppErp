
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.98.0">
    <title>Welcome Login - MyErp</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">

    <link href="<?php echo base_url() ?>assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
            font-size: 3.5rem;
            }
        }

        .b-example-divider {
            height: 3rem;
            background-color: rgba(0, 0, 0, .1);
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden;
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        html,
        body {
        height: 100%;
        }

        body {
        display: flex;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
        }

        .form-signin {
        max-width: 330px;
        padding: 15px;
        }

        .form-signin .form-floating:focus-within {
        z-index: 2;
        }

        .form-signin input[type="email"] {
        margin-bottom: -1px;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
        margin-bottom: 10px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        }

    </style>

    <!-- Custom styles for this template -->
    <!-- <link href="<?php echo base_url() ?>signin.css" rel="stylesheet"> -->
  </head>
  <body class="text-center">
    
    <?php
        $userID = $this->session->userdata('usidT');
        $password = $this->session->userdata('passT');
        
        $sel1 = '';
        $sel2 = '';
        $sel3 = '';
        $sel4 = '';
        $sel5 = '';
        $sel6 = '';
        $sel7 = '';
        $sel8 = '';
        $sel9 = '';
        $sel10 = '';
        $sel11 = '';
        $sel12 = '';
        $curYear = date('Y');
        $curMonth = date('m');
        switch($curMonth) {
            case '01': 
                $sel1 = 'selected';
                break;
            case '02': 
                $sel2 = 'selected';
                break;
            case '03': 
                $sel3 = 'selected';
                break;
            case '04': 
                $sel4 = 'selected';
                break;
            case '05': 
                $sel5 = 'selected';
                break;
            case '06': 
                $sel6 = 'selected';
                break;
            case '07': 
                $sel7 = 'selected';
                break;
            case '08': 
                $sel8 = 'selected';
                break;
            case '09': 
                $sel9 = 'selected';
                break;
            case '10': 
                $sel10 = 'selected';
                break;
            case '11': 
                $sel11 = 'selected';
                break;	
            case '12': 
                $sel12 = 'selected';
                break;	
        }
    ?>
    <main class="form-signin w-100 m-auto">
        <form method="POST" action="<?php echo base_url('loginMe') ?>">
            <img class="mb-4" src="<?php echo base_url() ?>assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Please Log In</h1>
            <?php echo $this->session->flashdata('message') ?>
            <div class="form-floating">
                <input type="text" name="email" id="email" class="form-control" id="floatingInput" placeholder="name@example.com" autocomplete="off">
                <label for="floatingInput">User ID</label>
            </div>
            <div class="form-floating">
                <input type="password" name="password" id="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>
            <label class="bg-secondary form-control text-white rounded">Pilih Periode</label> 
            <div class="form-floating">
                <div class="row">
                    <div class="col-lg-4">
                        <select class="form-select" aria-label="Default select example" id="month" name="month" onchange="javascript: getValue();>
                            <option value="01" <?=$sel1?>>Jan</option>
                            <option value="02" <?=$sel2?>>Feb</option>
                            <option value="03" <?=$sel3?>>Mar</option>
                            <option value="04" <?=$sel4?>>Apr</option>
                            <option value="05" <?=$sel5?>>Mei</option>
                            <option value="06" <?=$sel6?>>Jun</option>
                            <option value="07" <?=$sel7?>>Jul</option>
                            <option value="08" <?=$sel8?>>Agt</option>
                            <option value="09" <?=$sel9?>>Sep</option>
                            <option value="10" <?=$sel10?>>Okt</option>
                            <option value="11" <?=$sel11?>>Nov</option>
                            <option value="12" <?=$sel12?>>Des</option>
                        </select>
                        <!-- <label for="floatingPeriode">Bulan</label>  -->
                    </div>
                    <div class="col-lg-8">
                        <input type="text" name="year" value="<?=$curYear?>" placeholder="Tahun" maxlength="4" style="text-align: center" onKeyPress="return isNumberKey(event)" required autocomplete="off" class="form-control" id="floatingPassword" > 
                        <!-- <label for="floatingPassword">Tahun</label>  -->
                    </div>
                </div>
            </div>  
            <!-- <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div> -->
            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit">Log in</button>
            <p class="mt-5 mb-3 text-muted">&copy; MyErp <?= date('Y') ?></p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    
  </body>
</html>
