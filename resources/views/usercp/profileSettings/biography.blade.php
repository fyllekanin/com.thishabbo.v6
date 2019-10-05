<div class="content-holder" id="biography">
    <div class="content">
        <div class="contentHeader headerRed">
            <span>Edit Biography</span>
        </div>
        <textarea id="edit-user-bio" class="login-form-input" style="height: 4rem;" maxlength="250">{!! Auth::user()->bio !!}</textarea>
        <br />
        (<span id="len">0</span>/250)
        <br /><br />
        <button class="pg-red headerRed gradualfader fullWidth topBottom" onclick="saveBio();">Save</button>
    </div>
</div>