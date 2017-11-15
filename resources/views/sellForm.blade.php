<Form>
    <form action="category" method="post">
        商品名稱:<input type="text" name="title" required><br>
        商品類別:<select name="category" required>
        @for ($i = 0; $i < count($category); $i++)
                　<option value={{$category[$i]->id}}>{{$category[$i]->product_type}}</option>
        @endfor
        </select><br>
        價格:<input type="text" name="price" required><br>
        上架日期:<input type="datetime" name="expiration_date" required><br>

        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <input type="submit" value="送出">
    </form>
</Form>


