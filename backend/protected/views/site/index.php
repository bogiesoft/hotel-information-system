<div class="row mb10">
    <div class="col-md-3">
        <div class="panel bg-alert light of-h mb10">
            <div class="pn pl20 p5">
                <div class="icon-bg"> <i class="fa fa-user"></i> </div>
                <h2 class="mt15 lh15"> <b><?php echo MCustomer::model()->count(); ?></b> </h2>
                <h5 class="text-muted">Customers</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel bg-info light of-h mb10">
            <div class="pn pl20 p5">
                <div class="icon-bg"> <i class="fa fa-hotel"></i> </div>
                <h2 class="mt15 lh15"> <b><?php echo MRoomNumber::model()->count(); ?></b> </h2>
                <h5 class="text-muted">Rooms</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel bg-danger light of-h mb10">
            <div class="pn pl20 p5">
                <div class="icon-bg"> <i class="fa fa-spoon"></i> </div>
                <h2 class="mt15 lh15"> <b><?php echo MMenu::model()->count(); ?></b> </h2>
                <h5 class="text-muted">Food Menu</h5>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="panel bg-warning light of-h mb10">
            <div class="pn pl20 p5">
                <div class="icon-bg"> <i class="fa fa-user-secret"></i> </div>
                <h2 class="mt15 lh15"> <b><?php echo User::model()->count(); ?></b> </h2>
                <h5 class="text-muted">User</h5>
            </div>
        </div>
    </div>
</div>