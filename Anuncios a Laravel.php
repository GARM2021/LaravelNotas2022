<?php 

Transcribir el siguiente codigo a  PHP 5.6.40  Laravel 5.4  composer 2.0.011  

                        <td class="style24">
                            Seleccione Col:&nbsp;&nbsp;&nbsp;
                            <asp:DropDownList ID="ddlCol" runat="server" AutoPostBack="True" CssClass="ddl" 
                                DataSourceID="SDSDDLCol" DataTextField="nomcol" DataValueField="colonia" 
                                Font-Names="Verdana" Font-Size="X-Small" Height="17px" 
                                onselectedindexchanged="ddlCol_SelectedIndexChanged" Width="287px">
                            </asp:DropDownList>
                        </td>

<td class="style24">
    Seleccione Col:&nbsp;&nbsp;&nbsp;
    <select id="ddlCol" class="ddl" onchange="this.form.submit()">
        <option value=""></option>
        @foreach ($colonias as $colonia)
            <option value="{{ $colonia->colonia }}">{{ $colonia->nomcol }}</option>
        @endforeach
    </select>
</td>

?>
