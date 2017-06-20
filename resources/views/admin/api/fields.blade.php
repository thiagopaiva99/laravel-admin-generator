<div class="form-group col-md-3">
    <label for="">Escolha uma tabela:</label>
    <select name="table" id="" class="form-control">
        @foreach($tables as $table)
            <option value="{!! $table->table_name !!}">{!! $table->table_name !!}</option>
        @endforeach
    </select>
</div>

<div class="form-group col-md-3">
    <label for="">Tipo:</label>
    <select name="type" class="form-control">
        <option value="id">Get by ID</option>
        <option value="all">Get All</option>
        <option value="store">Store</option>
        <option value="delete">Delete</option>
        <option value="update">Update</option>
        <option value="login">Login</option>
    </select>
</div>

<div id="attributes" class="col-md-12"></div>

<div class="col-md-12">
    <hr>
    <div class="form-group">
        <button type="button" class="btn btn-primary btn-generate-api" disabled>Gerar API</button>
    </div>
</div>

<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Codigo da API</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>