<div class="content-holder">
    <div class="content contentpadding">
        <div class="contentHeader headerRed">
            <span>Profile Settings - Quick Links</span>
        </div>
        <table>
            <thead style="text-align: center;">
                <th id="editpostbit" style="width: 16.6%; text-align: center;"><b><a>Edit Postbit</a></b></th>
                <th id="editnameicon" style="width: 16.6%; text-align: center;"><b><a>Edit Name Icon</a></b></th>
                <th id="editnameeffect" style="width: 16.6%; text-align: center;"><b><a>Edit Name Effect</a></b></th>
                <th id="editbackground" style="width: 16.6%; text-align: center;"><b><a>Edit Background</a></b></th>
            </thead>
            <thead style="text-align: center;">
                <th id="editprofilebadges" style="width: 16.6%; text-align: center;"><b><a>Edit Profile Badges</a></b></th>
                <th id="editbiography" style="width: 16.6%; text-align: center;"><b><a>Edit Biography</a></b></th>
                @if($UserHelper::haveSubFeature(Auth::user()->userid, 1))
                    @if($UserHelper::haveSubFeature(Auth::user()->userid, 2) OR $UserHelper::haveSubFeature(Auth::user()->userid, 4) OR $UserHelper::haveSubFeature(Auth::user()->userid, 8))
                        <th id="editusernamefatures" style="width: 16.6%; text-align: center;"><b><a>Username Features</a></b></th>
                    @endif
                    @if($UserHelper::haveSubFeature(Auth::user()->userid, 16) OR $UserHelper::haveSubFeature(Auth::user()->userid, 32) OR $UserHelper::haveSubFeature(Auth::user()->userid, 64))
                        <th id="edituserbarfeatures" style="width: 16.6%; text-align: center;"><b><a>Userbar Features</a></b></th>
                    @endif
                @endif
            </thead>
        </table>
    </div>
</div>
