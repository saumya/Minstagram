//
// version: 1.1.0
//
// self executing function
// (function(){})();
//
(function(msg){
    //
    const btnUpdate = document.getElementById('id_btn_update');
    const txtUpdate = document.getElementById('id_txt_update');
    const btnRemake = document.getElementById('id_btn_remake_data');
    //
    // hide the progress bars
    const hideProgressbars = function(){
        const p1 = document.getElementById('progress_1');
        const p2 = document.getElementById('progress_2');
        const p3 = document.getElementById('progress_3');

        p1.style.display = 'none';
        p2.style.display = 'none';
        p3.style.display = 'none';
    };
    const showProgressBarWithId = function(id){
        const el = document.getElementById(id);
        el.style.display = '';
    };
    const hideProgressBarWithId = function(id){
        const el = document.getElementById(id);
        el.style.display = 'none';
    };
    // 
    const updateDataForTheImage = function(){
        const url = 'update_info_in_db.php';
        const pData = {
            'name' : 'Saumya',
            'title' : txtUpdate.value
        };
        //
        fetch( url,{
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: ( JSON.stringify(pData) )
        }).then( response=>{
            //console.log( 'status=', response.statusText );
            hideProgressbars();
            response.text().then(result=>{
               console.log('=========================');
               console.log( 'result=', result );
               console.log('=========================');
            }).catch( error1=>console.log(error1) );
        }).catch( error1=>console.log(error1) );
        //
    } // updateDataForTheImage/

    // Btn Click    
    btnUpdate.addEventListener('click', (event)=>{
      event.preventDefault();
      showProgressBarWithId('progress_2');
      updateDataForTheImage()
    });// Btn Click/
    //
    btnRemake.addEventListener('click', event=>{
        event.preventDefault();
        // Rewrite the JSON file by getting data from the SQLite
        //
        const url = 'make_json_file_from_db.php';
        const pData = {};
        //fetch( url, {}).then().catch();
        fetch( url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: ( JSON.stringify(pData) )
        }).then( response=>{
            response.text().then( result=>console.log(result) ).catch( error2=>console.log(error2) );
        } ).catch( error1=> console.log(error1) );
    });
    
    console.log('update.js', msg);
})('1.1.0');
// self executing function/
