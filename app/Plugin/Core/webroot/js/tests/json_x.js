QUnit.test('/core/json_x.js tests', function( assert ) {
    var data = {
        model: {
            row: {
                key: 123
            }
        },
        model2: {
            row2: {
                key2: 1234
            }
        }
    };
    assert.strictEqual(JSON.get(data, 'model2.row2.key2'), 1234, 'Correct extraction from data');
    assert.strictEqual(JSON.get(data, 'model2.row.key2'), null, 'Incorrect extraction from data');
    assert.strictEqual(JSON.get({id: 1}, 'id'), 1, '1st level extraction from 1-level data');
    assert.strictEqual(JSON.get({id: 1}, 'col'), null, 'Incorrect 1st level extraction from 1-level data');
    assert.deepEqual(JSON.get(data, 'model2'), {row2: {key2: 1234}}, 'Extract nested data');

    var a = 1;
    assert.ok(isset(a), 'isset() for variable');
    var a = [];
    assert.ok(isset(a), 'isset() for array');
    var a = {};
    assert.notOk(isset(a.b), 'isset() for undefined');
    assert.notOk(isset(null), 'isset() for null');

    var data = [
        {key: 123, title: 'row1'},
        {key: 1234, title: 'row3'},
        {key: 222, title: 'row2'}
    ];
    assert.deepEqual(JSON.getBy(data, 'key', 1234), {key: 1234, title: 'row3'}, 'getBy() found element');
    assert.strictEqual(JSON.getBy(data, 'key', 111), null, 'getBy() element not found');

    var data = [
        {key: 123, title: 'row1'},
        {key: 1234, title: 'row2'},
        {key: 222, title: 'row4'},
        {key: 9, title: 'row3'}
    ];
    var expected = [
        {key: 123, title: 'row1'},
        {key: 1234, title: 'row2'},
        {key: 9, title: 'row3'},
        {key: 222, title: 'row4'}
    ];
    assert.deepEqual(JSON.sortBy(data, 'title'), expected, 'sortBy() sort by string');
    var expected = [
        {key: 9, title: 'row3'},
        {key: 123, title: 'row1'},
        {key: 222, title: 'row4'},
        {key: 1234, title: 'row2'}
    ];
    assert.deepEqual(JSON.sortBy(data, 'key'), expected, 'sortBy() sort by number');
});