#define BLOCK_SIZE         		3
#define BOARD_MAX_WIDTH         10
#define BOARD_MAX_HEIGHT        900

float board[BOARD_MAX_WIDTH][BOARD_MAX_HEIGHT];
int height[BOARD_MAX_WIDTH];
int boardWidth, blockX, blockType, totalSyms, specialSym, blockCirc;
int block[BLOCK_SIZE];

void init(int width) {
	for (int i = 0; i < width; ++i) {
		for (int j = 0; j < BOARD_MAX_HEIGHT; ++j)
			board[i][j] = 0;
		height[i] = 0;
	}
	boardWidth = width;
	totalSyms = 0;
}

void newBlock(int input[BLOCK_SIZE]) {
	for (int i = 0; i < BLOCK_SIZE; ++i)
		block[i] = input[i];
	blockX = 0;
	blockType = 0;
	blockCirc = 0;
}

void specialBlock(int type) {
	blockX = 0;
	blockType = type;
}

void doCirculate() {
	blockCirc %= 3;
	int temp;
	switch (blockCirc) {
	case 1:
		temp = block[2];
		block[2] = block[1];
		block[1] = block[0];
		block[0] = temp;
		break;
	case 2:
		temp = block[0];
		block[0] = block[1];
		block[1] = block[2];
		block[2] = temp;
		break;
	}
}

void circulate(int count) {
	blockCirc += count;
}

void move(int distance) {
	blockX += distance;
	if (blockX >= boardWidth)
		blockX = boardWidth - 1;
	else if (blockX < 0)
		blockX = 0;
}

inline int toBeDestroyed(float x)
{
	return (x - int(x) != 0); //check this
}

inline int areEqual(float x, float y)
{
	return (int(x) == int(y));
}

inline void setDestroy(int x, int y)
{
	if (!toBeDestroyed(board[x][y]))
		board[x][y] += 0.5f;
}

int doDestroy()
{
	int destroyed = 0;
	for (int i = 0; i < boardWidth; ++i) {
		int gap = 0;
		for (int j = 0; j < height[i]; ++j) {
			if (toBeDestroyed(board[i][j]))
				++gap;
			else
				board[i][j - gap] = board[i][j];
		}
		height[i] -= gap;
		for (int j = height[i]; j < height[i] + gap; ++j)
			board[i][j] = 0;
		destroyed += gap;
	}
	return destroyed;
}

void dfs(int x, int y)
{
	board[x][y] += 0.5f;
	for (int i = x - 1; i <= x + 1; ++i)
	{
		if (i < 0 || i >= boardWidth)
			continue;
		for (int j = y - 1; j <= y + 1; ++j)
		{
			if (j < 0 || j >= height[i])
				continue;
			if (!toBeDestroyed(board[i][j]) && areEqual(board[x][y], board[i][j]))
				dfs(i, j);
		}
	}
}

int specialLand()
{
	if (height[blockX] == 0)
		return 0;
	specialSym = board[blockX][height[blockX] - 1];
	if (blockType == 1) {
		for (int i = 0; i < boardWidth; ++i)
		for (int j = 0; j < height[i]; ++j)
		if (board[i][j] == specialSym)
			board[i][j] += 0.5f;
	}
	else {
		dfs(blockX, height[blockX] - 1);
	}
	doDestroy();
	return 1;
}
/*
void checkHorizontal()
{
for (int i = 0; i < boardWidth - 2; ++i)
for (int j = 0; j < height[i]; ++j)
if (j < height[i+1] && j < height[i+2] && areEqual(board[i][j], board[i+1][j]) && areEqual(board[i+1][j], board[i+2][j])) {
setDestroy(i, j);
setDestroy(i + 1, j);
setDestroy(i + 2, j);
}
}

void checkVeritcal()
{
for (int i = 0; i < boardWidth; ++i)
for (int j = 0; j < height[i] - 2; ++j)
if (areEqual(board[i][j], board[i][j+1]) && areEqual(board[i][j+1], board[i][j+2])) {
setDestroy(i, j);
setDestroy(i, j + 1);
setDestroy(i, j + 2);
}
}

void checkDiagonal()
{
for (int i = 0; i < boardWidth - 2; ++i)
for (int j = 0; j < height[i]; ++j) {
if (j >= 2 && j - 1 < height[i + 1] && j - 2 < height[i + 2] && areEqual(board[i][j], board[i + 1][j - 1]) && areEqual(board[i + 1][j - 1], board[i + 2][j - 2])) {
setDestroy(i, j);
setDestroy(i + 1, j - 1);
setDestroy(i + 2, j - 2);
}
if (j + 1 < height[i + 1] && j + 2 < height[i + 2] && areEqual(board[i][j], board[i + 1][j + 1]) && areEqual(board[i + 1][j + 1], board[i + 2][j + 2])) {
setDestroy(i, j);
setDestroy(i + 1, j + 1);
setDestroy(i + 2, j + 2);
}
}
}
*/
void checkDestroy()
{
	do {
		for (int i = 0; i < boardWidth; ++i)
		for (int j = 0; j < height[i]; ++j) {
			if (j < height[i] - 2 && areEqual(board[i][j], board[i][j + 1]) && areEqual(board[i][j + 1], board[i][j + 2])) { /* Vertical check */
				setDestroy(i, j);
				setDestroy(i, j + 1);
				setDestroy(i, j + 2);
			}
			if (i < boardWidth - 2 && j < height[i + 1] && j < height[i + 2] && areEqual(board[i][j], board[i + 1][j]) && areEqual(board[i + 1][j], board[i + 2][j])) { /* Horizontal check */
				setDestroy(i, j);
				setDestroy(i + 1, j);
				setDestroy(i + 2, j);
			}
			if (i < boardWidth - 2 && j >= 2 && j - 1 < height[i + 1] && j - 2 < height[i + 2] && areEqual(board[i][j], board[i + 1][j - 1]) && areEqual(board[i + 1][j - 1], board[i + 2][j - 2])) { /* Diagonal down check */
				setDestroy(i, j);
				setDestroy(i + 1, j - 1);
				setDestroy(i + 2, j - 2);
			}
			if (i < boardWidth - 2 && j + 1 < height[i + 1] && j + 2 < height[i + 2] && areEqual(board[i][j], board[i + 1][j + 1]) && areEqual(board[i + 1][j + 1], board[i + 2][j + 2])) { /* Diagonal up check */
				setDestroy(i, j);
				setDestroy(i + 1, j + 1);
				setDestroy(i + 2, j + 2);
			}
		}
	} while (doDestroy());
}

void calcTotalSyms()
{
	totalSyms = 0;
	for (int i = 0; i < boardWidth; ++i)
		totalSyms += height[i];
}

int land() {
	if (blockType) {
		if (specialLand() == 0)
			return totalSyms;
	}
	else {
		doCirculate();
		for (int i = BLOCK_SIZE - 1; i >= 0; --i)
			board[blockX][height[blockX]++] = block[i];
	}
	checkDestroy();
	calcTotalSyms();
	return totalSyms;
}
