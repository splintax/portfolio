countAnswerLength = (cells, i, j, direction) ->
    length = 0
    if direction is 'across'
        while j < cells[i].length and cells[i][j].class isnt 'black'
            j++; length++
    if direction is 'down'
        while i < cells.length and cells[i][j].class isnt 'black'
            i++; length++
    return length

# Step over the cells of a crossword grid and number them,
# returning a dictionary of empty clues.
analyseGrid = (crossword) ->
    counter = 0
    clues = {across: {}, down: {}}
    cells = crossword.cells
    for row, i in cells
        for cell, j in row when cell.class isnt 'black'
            up = if i > 0 then (cells[i-1][j].class isnt 'black') else false
            down = if i+1 < cells.length then (cells[i+1][j].class isnt 'black') else false
            left = if j > 0 then (row[j-1].class isnt 'black') else false
            right = if j+1 < row.length then (row[j+1].class isnt 'black') else false
            if right and not left   # across clue
                cell.num = ++counter
                if not clues.across[cell.num]?
                    clues.across[cell.num] =
                        length: countAnswerLength cells, i, j, 'across'
            if down and not up      # down clue
                if not right or left
                    cell.num = ++counter
                if not clues.down[cell.num]?
                    clues.down[cell.num] =
                        length: countAnswerLength cells, i, j, 'down'
    return clues

class Crossword
    constructor: (@crosswordString, @width, @height) ->
        if crosswordString?                 # explicitly constructed
            @load crosswordString
        else if localStorage['cryptical']?  # try local storage
            @load localStorage['cryptical']
        else                                # generate empty
            @width = 10; @height = 10; @random()
        
    load: (crosswordString) ->
        console.log 'loading from this JSON:', crosswordString
        newCrossword = angular.fromJson(crosswordString)
        @cells = ((new Cell char for char in row) for row in newCrossword.puzzle)
        # number grid, obtain empty clue dict
        @clues = analyseGrid @
        # but if we got clues, then replace the empty dict with those
        if newCrossword.clues? then @clues = newCrossword.clues 
        @title = newCrossword.title or '(untitled)'
        @author = newCrossword.author or '(anonymous)'
        @width = @cells[0].length
        @height = @cells.length
    toggleCells = false
    editable = 'clues'
    serialise: ->
        stringify_cell = (cell) ->
            if cell.char == '' or cell.class == 'black' then return '@'
            else return cell.char
        stringify_row = (row) ->
            (stringify_cell(cell) for cell in row).join('')
        puzzle = (stringify_row(row) for row in @cells)
        return angular.toJson
            puzzle: puzzle
            clues: @clues
            title: @title
            author: @author
    random: ->
        console.log 'generating random...'
        randomChar = ->
            validChars = '@ABCD'
            validChars[Math.floor( Math.random()*validChars.length )]
        rowGen = (rowIndex, cols) -> (randomChar() for _ in [0..cols-1]).join('')
        @load angular.toJson
            puzzle: (rowGen r, @width for r in [0..@height-1])
    blank: ->
        console.log 'generating blank...'
        @load angular.toJson
            puzzle: ((' ' for _ in [0..@width-1]).join('') for _ in [0..@height-1])

class Cell
    constructor: (@char, @num) ->
        @char = @char.toUpperCase()
        if @char is '@'
            @char = ''
            @class = 'black'
    clean: ->
        # Called whenever the cell is changed.
        @char = @char.slice(-1).toUpperCase()
    toggle: ->
        console.warn 'Puzzle layout changed, may need renumbering.'
        if @class is 'black' then @class = ''
        else @class = 'black'

# Here we initiate the Angular module by constructing a
# Crossword object. So initialisation code can be found inside
# Crossword's constructor...
cryptical = angular.module 'cryptical', []

# Used to sort a hash of clues by their keys; AngularJS's orderBy
# filter won't do this automatically.
# See: http://stackoverflow.com/a/18186947/1402935
cryptical.filter 'sortObjectKeys', -> (input) ->
    value.num = key for key, value of input
    array = (value for key, value of input)
    array.sort (a, b) -> parseInt(a.num) - parseInt(b.num)

cryptical.controller 'CrosswordCtrl', ['$scope',
    ($scope) ->
        $scope.crossword = new Crossword()
        $scope.forceUppercase = (char) ->
            console.log char
            str = 'f'
            @value = str.toUpperCase()
]

cryptical.controller 'CrosswordLoader', ['$scope', 
    ($scope) ->
        $scope.puzzles = localStorage['cryptical']
        console.log 'puzzles:', $scope.puzzles
]

cryptical.controller 'CluesCtrl', ['$scope',
    ($scope) ->
        console.log 'initialising clues controller'
]

cryptical.controller 'ButtonsCtrl', ['$scope',
    ($scope) ->
        $scope.load_local = ->
            $scope.crossword.load localStorage['cryptical']
        $scope.save_local = ->
            data = $scope.crossword.serialise()
            console.log 'saving this locally:', data
            localStorage['cryptical'] = data
        $scope.refreshClues = ->
            $scope.crossword.clues = analyseGrid($scope.crossword)
]
