//
// version: 1.0.0
//

(message=>{
    console.log('Settings version', message);

    const btnInstallDB = document.getElementById('id_btn_installDB');

    const init_db_url = 'init_db.php';

    const installDB = function(){
        console.log('installDB');
        //TODO: call server for the DB initialisation
        console.log('dbURL', init_db_url);
        const pData = {
            'name': 'MinstagramUI'
        };
    };
    // Event Handlers
    btnInstallDB.addEventListener('click', event=>{
        event.preventDefault();
        console.log('Initialise Database');
        installDB();
    });

})('1.0.0');